<?php

namespace WarbleMedia\PhoenixBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use WarbleMedia\PhoenixBundle\Model\UserInterface;

class DeveloperVoter extends Voter
{
    /** @var array */
    private $developerEmails;

    /**
     * DeveloperVoter constructor.
     *
     * @param array $developerEmails
     */
    public function __construct(array $developerEmails)
    {
        $this->developerEmails = $developerEmails;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        return $attribute === UserInterface::ROLE_DEVELOPER;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if ($user instanceof UserInterface) {
            return in_array($user->getEmail(), $this->developerEmails, true);
        }

        return false;
    }
}
