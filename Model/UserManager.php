<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserManager implements UserManagerInterface
{
    /** @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface */
    protected $encoderFactory;

    /** @var \Doctrine\Common\Persistence\ObjectManager */
    protected $manager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    protected $repository;

    /** @var string */
    protected $userClass;

    /**
     * UserManager constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     * @param \Doctrine\Common\Persistence\ObjectManager                       $manager
     * @param string                                                           $userClass
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, ObjectManager $manager, string $userClass)
    {
        $this->encoderFactory = $encoderFactory;
        $this->manager = $manager;
        $this->repository = $manager->getRepository($userClass);
        $this->userClass = $userClass;
    }

    /**
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface
     */
    public function createUser(): UserInterface
    {
        return new $this->userClass;
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     * @param bool                                           $flush
     */
    public function updateUser(UserInterface $user, bool $flush = true)
    {
        $this->updatePassword($user);

        $this->manager->persist($user);

        if ($flush) {
            $this->manager->flush();
        }
    }

    /**
     * @param string $email
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface|null
     */
    public function findUserByEmail(string $email)
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    /**
     * @param string $token
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface|null
     */
    public function findUserByConfirmationToken(string $token)
    {
        return $this->repository->findOneBy(['confirmationToken' => $token]);
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     */
    protected function updatePassword(UserInterface $user)
    {
        $password = $user->getPlainPassword();

        if (strlen($password) !== 0) {
            $encoder = $this->getEncoder($user);
            $password = $encoder->encodePassword($password, $user->getSalt());
            $user->setPassword($password);
            $user->eraseCredentials();
        }
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    private function getEncoder(UserInterface $user): PasswordEncoderInterface
    {
        return $this->encoderFactory->getEncoder($user);
    }
}
