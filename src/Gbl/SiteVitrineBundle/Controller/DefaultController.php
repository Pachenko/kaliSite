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
     * Page index du site
     * 
     * @Route("/", name="site.index")
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
    	
    	$panier = $this->get('session')->get('panier');
    	    	
    	return $this->render('GblSiteVitrineBundle:Default:index.html.twig', array(
    			'theme' 	 => $infoTheme,
    			'categories' => $infoCat,
    			'panier'	 => $panier,
    	));
    }
    
    /**
     * Liste les 10 produits les plus vendus
     * 
     * @Route("/top10", name="site.top10")
     */
    public function top10Action()
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
    	//	API pour top 10 produits  //
    	////////////////////////////////
    	
    	$produits = $browser->get('http://back.kali.com/api/top10/produit');
    	 
    	//Tableau des infos config
    	$infoProd = json_decode($produits->getContent(), true);
    	
    	//var_dump($infoCat); die();
    	 
    	if (!$infoProd) {
    		throw new NotFoundHttpException(sprintf('Produit top 10 introuvable'));
    	}
    	
    	//var_dump($infoProd);die();
    	 
    	return $this->render('GblSiteVitrineBundle:Default:top10.html.twig', array(
    			'theme' 	 => $infoTheme,
    			'categories' => $infoCat,
    			'produits'	 => $infoProd,
    			
    	));
    }
    
    /**
     * Liste tous les produits qui sont en vente flash
     * 
     * @Route("/flash", name="site.flash")
     */
    public function ventePriveeAction()
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
    	//	API pour top 10 produits  //
    	////////////////////////////////
    	 
    	$produits = $browser->get('http://back.kali.com/api/vente/flash');
    
    	//Tableau des infos config
    	$infoProd = json_decode($produits->getContent(), true);
    	 
    	//var_dump($infoCat); die();
    
    	if (!$infoProd) {
    		throw new NotFoundHttpException(sprintf('Produits introuvable'));
    	}
    	 
    	//var_dump($infoProd);die();
    
    	return $this->render('GblSiteVitrineBundle:Default:flash.html.twig', array(
    			'theme' 	 => $infoTheme,
    			'categories' => $infoCat,
    			'produits'	 => $infoProd,
    			 
    	));
    }
}
