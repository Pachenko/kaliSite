<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Buzz\Browser as Browser;

class DefaultController extends Controller
{	
    /**
     * Page index du site
     * 
     * @Route("/", name="site.index")
     * @Template()
     */
    public function indexAction()
    {
    	return array(
    		'theme' 	 => $this->get('gbl.api_manager')->getTheme(),
    		'categories' => $this->get('gbl.api_manager')->getCategories(),
    		'panier'	 => $this->get('session')->get('panier'),
    	);
    }
    
    /**
     * Liste les 10 produits les plus vendus
     * 
     * @Route("/top10", name="site.top10")
     * @Template()
     */
    public function top10Action()
    {	
    	return array(
    		'theme' 	 => $this->get('gbl.api_manager')->getTheme(),
    		'categories' => $this->get('gbl.api_manager')->getCategories(),
    		'produits'	 => $this->get('gbl.api_manager')->getTop(),
    	);
    }
    
    /**
     * Liste tous les produits qui sont en vente flash
     * 
     * @Route("/flash", name="site.flash")
     * @Template()
     */
    public function flashAction()
    {
    	return array(
    		'theme' 	 => $this->get('gbl.api_manager')->getTheme(),
    		'categories' => $this->get('gbl.api_manager')->getCategories(),
    		'produits'	 => $this->get('gbl.api_manager')->getVenteFlash(),	 
    	);
    }
}
