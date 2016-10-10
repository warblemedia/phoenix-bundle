<?php

namespace WarbleMedia\PhoenixBundle\Model;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserManager implements UserManagerInterface
{
    /** @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface */
    protected $encoderFactory;

    /** @var \Doctrine\ORM\EntityManager */
    protected $manager;

    /** @var \Doctrine\ORM\EntityRepository */
    protected $repository;

    /** @var string */
    protected $userClass;

    /**
     * UserManager constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     * @param \Doctrine\ORM\EntityManager                                      $manager
     * @param string                                                           $userClass
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManager $manager, string $userClass)
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
     * @param mixed $id
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface|null
     */
    public function findUserById($id)
    {
        return $this->repository->find($id);
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
     * @param string                                         $search
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $exclude
     * @return \WarbleMedia\PhoenixBundle\Model\UserInterface[]
     */
    public function findUsersBySearchString(string $search, UserInterface $exclude = null)
    {
        $search = str_replace('*', '%', $search);
        $hasWildcard = strpos($search, '%') !== false;
        $params = [
            'search_email' => $search,
            'search_name'  => $hasWildcard ? $search : '%' . $search . '%',
        ];

        $dql = 'SELECT u ' .
               "FROM {$this->userClass} u " .
               'WHERE (u.email LIKE :search_email OR u.name LIKE :search_name)';

        if ($exclude) {
            $dql .= ' AND u.id != :exclude';

            $params['exclude'] = $exclude;
        }

        $query = $this->manager->createQuery($dql);
        $query->setParameters($params);

        return $query->getResult();
    }

    /**
     * @param \WarbleMedia\PhoenixBundle\Model\UserInterface $user
     */
    protected function updatePassword(UserInterface $user)
    {
        $password = $user->getPlainPassword();

        if (!empty($password)) {
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
