<?php

namespace Gbl\SiteVitrineBundle\Security\Provider;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
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
		$response = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/users/' . $username);
		
		//Tableau des infos d'user
		$infoUser = json_decode($response->getContent(), true);

		if (array_key_exists(0, $infoUser)) {
			throw new UsernameNotFoundException(sprintf('Il n\'y a pas d\'utilisateur avec le nom :  "%s".', $username));
		}
		
		$user = $this->userManager->findUserByUsernameOrEmail($username);

		return $user;
	}

	public function refreshUser(UserInterface $user)
	{
		return $this->userManager->refreshUser($user);
	}

	public function supportsClass($class)
	{
		return $this->userManager->supportsClass($class);
	}
}