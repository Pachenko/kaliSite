<?php

namespace Gbl\SiteVitrineBundle\Security\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use FOS\UserBundle\Model\User;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Propel\User as PropelUser;
use Buzz\Browser;

class MyProvider implements UserProviderInterface
{
	private $userManager;

	public function __construct(UserManagerInterface $userManager)
	{
		$this->userManager = $userManager;
	}

	public function loadUserByUsername($username)
	{	
		$browser = new Browser();
		$response = $browser->get('http://back.kali.com/api/users/' . $username);

		$infoUser = json_decode($response->getContent(), true);

		if (array_key_exists(0, $infoUser)) {
			throw new UsernameNotFoundException(sprintf('Il n\'y a pas d\'utilisateur avec le nom :  "%s".', $username));
		}
		
		$user = $this->userManager->findUserByUsernameOrEmail($username);

		return $user;
	}

    /**
     * {@inheritDoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        if (!$user instanceof User && !$user instanceof PropelUser) {
            throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\User, but got "%s".', get_class($user)));
        }

        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', $this->userManager->getClass(), get_class($user)));
        }

        if (null === $reloadedUser = $this->userManager->findUserBy(array('id' => $user->getId()))) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }

    /**
     * Finds a user by username.
     *
     * This method is meant to be an extension point for child classes.
     *
     * @param string $username
     *
     * @return UserInterface|null
     */
    protected function findUser($username)
    {
        return $this->userManager->findUserByUsername($username);
    }
}