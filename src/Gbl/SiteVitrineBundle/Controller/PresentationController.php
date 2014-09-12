<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Buzz\Browser;
use Ivory\GoogleMapBundle\IvoryGoogleMapBundle;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\MarkerShape;

class PresentationController extends Controller
{
	/**
	 * @Route("/presentation", name="site.presentation")
	 */
	public function indexAction()
	{
		//Création de l'API
    	$browser = new Browser();
    	
    	////////////////////////////////
    	//		API pour config		  //
    	////////////////////////////////
    	$response = $browser->get('http://back.kali.com/api/configurations/kaliSiteVitrine');   	
    	//Tableau des infos config
    	$infoConfig = json_decode($response->getContent(), true);  
    	
    	if (array_key_exists(0, $infoConfig)) {
    		throw new NotFoundHttpException(sprintf('Configuration inconnue'));
    	}   	
    	$id_theme = $infoConfig['theme']['id'];

    	//Recherche info thème
    	$responseTheme = $browser->get('http://back.kali.com/api/themes/'.$id_theme);
    	
    	$infoTheme = json_decode($responseTheme->getContent(), true);
    	
    	if (array_key_exists(0, $infoTheme)) {
    		throw new NotFoundHttpException(sprintf('Thème inconnue'));
    	}
    	
    	////////////////////////////////
    	//	  API pour catégories	  //
    	////////////////////////////////
    	
    	$categories = $browser->get('http://back.kali.com/api/categories');
		
		//Tableau des infos config
    	$infoCat = json_decode($categories->getContent(), true);
    	
    	//var_dump($infoCat); die();
    	 
    	if (!$infoCat) {
    		throw new NotFoundHttpException(sprintf('Catégories introuvable'));
    	}
    	
    	////////////////////////////////
    	//		 API Google MAP	 	  //
    	////////////////////////////////
    	
    	$map = new Map();
    	$marker = new Marker();
    	$markerShape = new MarkerShape();
    	
    	$map->setMapOption('zoom', 19);
    	$map->setCenter(48.8485578, 2.3885041, true);
    	
    	$map->setStylesheetOption('width', '400px');
    	$map->setStylesheetOption('height', '400px');
    	$map->setStylesheetOptions(array(
    			'width'  => '400px',
    			'height' => '400px',
    	));
    	
    	$map->setLanguage('fr');
    	
    	return $this->render('GblSiteVitrineBundle:Presentation:index.html.twig', array(
    			'theme' 	 => $infoTheme,
    			'categories' => $infoCat,
    			'map'		 => $map
    	));
	}	
}