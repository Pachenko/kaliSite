<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Buzz\Browser;
use Ivory\GoogleMapBundle\IvoryGoogleMapBundle;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;

class PresentationController extends Controller
{
	/**
	 * @Route("/presentation", name="presentation.index")
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
    	
    	////////////////////////////////
    	//		 API Google MAP	 	  //
    	////////////////////////////////
    	
    	$map = new Map();
    	
    	$map->setPrefixJavascriptVariable('map_');
    	$map->setHtmlContainerId('map_canvas');
    	
    	$map->setAsync(false);
    	$map->setAutoZoom(false);
    	
    	$map->setCenter(0, 0, true);
    	$map->setMapOption('zoom', 3);
    	
    	$map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);
    	
    	$map->setMapOption('mapTypeId', MapTypeId::ROADMAP);
    	$map->setMapOption('mapTypeId', 'roadmap');
    	
    	$map->setMapOption('disableDefaultUI', true);
    	$map->setMapOption('disableDoubleClickZoom', true);
    	$map->setMapOptions(array(
    			'disableDefaultUI'       => true,
    			'disableDoubleClickZoom' => true,
    	));
    	
    	$map->setStylesheetOption('width', '400px');
    	$map->setStylesheetOption('height', '400px');
    	$map->setStylesheetOptions(array(
    			'width'  => '400px',
    			'height' => '400px',
    	));
    	
    	$map->setLanguage('fr');
    	
    	//$map = $this->get('ivory_google_map.map');
    	
    	//var_dump($map);die();
    	
    	return $this->render('GblSiteVitrineBundle:Presentation:index.html.twig', array(
    			'theme' 	 => $infoTheme,
    			'categories' => $infoCat,
    			'map'		 => $map
    	));
	}	
}