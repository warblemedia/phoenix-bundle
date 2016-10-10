<?php

namespace WarbleMedia\PhoenixBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Mapping\RuntimeReflectionService;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use WarbleMedia\PhoenixBundle\Model\ModelInterface;

/**
 * This file is modified from the Sylius project.
 *
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class ORMMappedSuperClassSubscriber implements EventSubscriber
{
    /** @var \Doctrine\Common\Persistence\Mapping\RuntimeReflectionService */
    private $reflectionService;

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
     * @throws \Doctrine\ORM\ORMException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        if ($metadata->isMappedSuperclass) {
            // Remove illegal association from mapped superclass
            $this->unsetAssociationMappings($metadata);
        } else {
            // Push down associations from parent mapped superclasses
            $this->setAssociationMappings($metadata, $eventArgs->getEntityManager()->getConfiguration());
        }
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata
     * @param \Doctrine\ORM\Configuration             $configuration
     * @throws \Doctrine\ORM\ORMException
     */
    private function setAssociationMappings(ClassMetadataInfo $metadata, Configuration $configuration)
    {
        $mappedClasses = $configuration->getMetadataDriverImpl()->getAllClassNames();

        foreach (class_parents($metadata->getName()) as $parent) {
            // Ignore parent classes that aren't mapped in doctrine
            if (in_array($parent, $mappedClasses, true) === false) {
                continue;
            }

            $parentMetadata = new ClassMetadata($parent, $configuration->getNamingStrategy());
            $parentMetadata->wakeupReflection($this->getReflectionService());

            // Load Metadata
            $configuration->getMetadataDriverImpl()->loadMetadataForClass($parent, $parentMetadata);

            if ($this->isPhoenixModel($parentMetadata) === false) {
                continue;
            }

            if ($parentMetadata->isMappedSuperclass) {
                foreach ($parentMetadata->getAssociationMappings() as $key => $value) {
                    if ($this->hasRelation($value['type'])) {
                        $metadata->associationMappings[$key] = $value;
                    }
                }
            }
        }
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
     */
    private function unsetAssociationMappings(ClassMetadata $metadata)
    {
        if ($this->isPhoenixModel($metadata) === false) {
            return;
        }

        foreach ($metadata->getAssociationMappings() as $key => $value) {
            if ($this->hasRelation($value['type'])) {
                unset($metadata->associationMappings[$key]);
            }
        }
    }

    /**
     * @param int $type
     * @return bool
     */
    private function hasRelation($type)
    {
        $illegalAssociations = [
            ClassMetadataInfo::MANY_TO_MANY,
            ClassMetadataInfo::ONE_TO_MANY,
            ClassMetadataInfo::ONE_TO_ONE,
        ];

        return in_array($type, $illegalAssociations, true);
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadata $metadata
     * @return bool
     */
    private function isPhoenixModel(ClassMetadata $metadata)
    {
        if (!$reflClass = $metadata->getReflectionClass()) {
            return false;
        }

        return $reflClass->implementsInterface(ModelInterface::class);
    }

    /**
     * @return \Doctrine\Common\Persistence\Mapping\RuntimeReflectionService
     */
    private function getReflectionService()
    {
        if ($this->reflectionService === null) {
            $this->reflectionService = new RuntimeReflectionService();
        }

        return $this->reflectionService;
    }
}
