<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Buzz\Browser;

class PresentationController extends Controller
{
	/**
	 * @Route("/presentation/index", name="presentation.index")
	 */
	public function indexAction()
	{
		$map = $this->get('ivory_google_map.map');
				
		return $this->render('GblSiteVitrineBundle:Presentation:index.html.twig',$map);
	}	
}