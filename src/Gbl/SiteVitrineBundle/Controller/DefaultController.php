<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Buzz\Browser;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="site.index")
     */
    public function indexAction()
    {
    	//Création de l'API
    	$browser = new Browser();
    	
    	////////////////////////////////
    	//		API pour config		  //
    	////////////////////////////////
    	$response = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/configurations/kaliSiteVitrine');   	
    	//Tableau des infos config
    	$infoConfig = json_decode($response->getContent(), true);  
    	
    	if (array_key_exists(0, $infoConfig)) {
    		throw new NotFoundHttpException(sprintf('Configuration inconnue'));
    	}   	
    	$id_theme = $infoConfig['theme']['id'];

    	//Recherche info thème
    	$responseTheme = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/themes/'.$id_theme);
    	
    	$infoTheme = json_decode($responseTheme->getContent(), true);
    	
    	if (array_key_exists(0, $infoTheme)) {
    		throw new NotFoundHttpException(sprintf('Thème inconnue'));
    	}
    	
    	////////////////////////////////
    	//	  API pour catégories	  //
    	////////////////////////////////
    	
    	$categories = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/categories');
		
		//Tableau des infos config
    	$infoCat = json_decode($categories->getContent(), true);
    	
    	//var_dump($infoCat); die();
    	 
    	if (!$infoCat) {
    		throw new NotFoundHttpException(sprintf('Catégories introuvable'));
    	}
    	
    	return $this->render('GblSiteVitrineBundle:Default:index.html.twig', array(
    			'theme' 	 => $infoTheme,
    			'categories' => $infoCat
    	));
    }
}
