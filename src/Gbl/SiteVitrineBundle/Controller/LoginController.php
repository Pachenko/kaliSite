<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LoginController extends Controller
{
	/**
	 * @Route("/login")
	 */
	public function loginAction(Request $request)
	{
		$session = $request->getSession();
		
		if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
		} elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
			$error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
			$session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
		} else {
			$error = '';
		}
		
		if ($error) {
			$error = $error->getMessage();
		}

		$lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);
		
		$csrfToken = $this->container->has('form.csrf_provider')
		? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
		: null;
		
		return $this->renderLogin(array(
			'last_username' => $lastUsername,
			'error'         => $error,
			'csrf_token' => $csrfToken,
		));
	}
	
	protected function renderLogin(array $data)
	{
		$template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));
		
		return $this->container->get('templating')->renderResponse($template, $data);
	}
	
	/**
	 * Méthode Check override du Controleur Security de FOSUserBundle
	 * @throws \RuntimeException
	 */
	public function checkAction()
	{
		throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
	}
	
	/**
	 * Méthode Logout override du Controleur Security de FOSUserBundle
	 * @throws \RuntimeException
	 */
	public function logoutAction()
	{
		throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
	}
}