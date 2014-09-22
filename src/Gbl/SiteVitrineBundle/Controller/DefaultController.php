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
	 * Nom du site
	 * 
	 * @var String
	 */
	protected $_siteName = 'kaliSiteVitrine';
	
	/**
	 * Session
	 * 
	 * @var Session
	 */
	protected $_session = null;
	
	/**
	 * méthode file_get_content()
	 * 
	 * @var Buzz\Browser
	 */
	protected $_browser = null;
	
	/**
	 * Permet d'initialiser les données réutilisables
	 * 
	 */
	public function initializer()
	{
		/* Récupération de la session */
		$this->_session = $this->get('session');
		
		/* Création du bundle Browser */
		$this->_browser = new Browser();
		
		/* Initialisation des données */
		$config 	= $this->getConfig($this->_browser, $this->_siteName);
		$theme  	= $this->getTheme($this->_browser, $config['theme']['id']);
		$categories = $this->getCategories($this->_browser);
		
		/* Retourne un tableau de données */
		return array(
			'config' 	 => $config,
			'theme'  	 => $theme,
			'categories' => $categories,
		);
	}
	
    /**
     * Page index du site
     * 
     * @Route("/", name="site.index")
     * @Template()
     */
    public function indexAction()
    {
    	$initializer = $this->initializer();
    	    	
    	return array(
    		'theme' 	 => $initializer['theme'],
    		'categories' => $initializer['categories'],
    		'panier'	 => $this->_session->get('panier'),
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
		$initializer = $this->initializer();
    	
    	return array(
    		'theme' 	 => $initializer['theme'],
    		'categories' => $initializer['categories'],
    		'produits'	 => $this->getTop($this->_browser),	
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
    	$initializer = $this->initializer();
    	
    	return array(
    		'theme' 	 => $initializer['theme'],
    		'categories' => $initializer['categories'],
    		'produits'	 => $this->getVenteFlash($this->_browser),	 
    	);
    }
    
    /**
     * Permet de récupérer la config
     *
     */
    public function getConfig(Browser $browser, $site)
    {
    	$reponse = $browser->get('http://back.kali.com/api/configurations/' . $site);
    	$config  = json_decode($reponse->getContent(), true);
    
    	if (array_key_exists(0, $config)) {
    		throw new NotFoundHttpException(sprintf('Configuration inconnue'));
    	}
    
    	return $config;
    }
    
    /**
     * Permet de récupérer les thèmes
     *
     */
    public function getTheme(Browser $browser, $idTheme)
    {
    	$reponse =  $browser->get('http://back.kali.com/api/themes/' . $idTheme);
    	$theme   = json_decode($reponse->getContent(), true);
    
    	if (array_key_exists(0, $theme)) {
    		throw new NotFoundHttpException(sprintf('Thème inconnue'));
    	}
    
    	return $theme;
    }
    
    /**
     * Permet de récupérer les catégories
     *
     */
    public function getCategories(Browser $browser)
    {
    	$reponse	= $browser->get('http://back.kali.com/api/categories');
    	$categories = json_decode($reponse->getContent(), true);
    
    	if (!$categories) {
    		throw new NotFoundHttpException(sprintf('Catégories introuvable'));
    	}
    
    	return $categories;
    }
    
    /**
     * Permet de récupérer le Top10
     *
     */
    public function getTop(Browser $browser)
    {
    	$reponse  = $browser->get('http://back.kali.com/api/top10/produit');
    	$top	  = json_decode($reponse->getContent(), true);
    
    	if (!$prod) {
    		throw new NotFoundHttpException(sprintf('Produit top 10 introuvable'));
    	}
    
    	return $top;
    }
    
    /**
     * Permet de récupérer les ventes flash
     *
     */
    public function getVenteFlash(Browser $browser)
    {
    	$reponse    = $browser->get('http://back.kali.com/api/vente/flash');
    	$venteFlash = json_decode($reponse->getContent(), true);
    
    	if (!$venteFlash) {
    		throw new NotFoundHttpException(sprintf('Produits Vente Flash introuvable'));
    	}
    
    	return $venteFlash;
    }
}
