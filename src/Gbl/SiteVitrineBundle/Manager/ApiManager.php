<?php

namespace Gbl\SiteVitrineBundle\Manager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Buzz\Browser as Browser;

class ApiManager
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
	
	const API_CONFIGURATION = 'http://back.kali.com/api/configurations/';
	const API_THEME 		= 'http://back.kali.com/api/themes/';
	const API_TRANSPORTEUR  = 'http://back.kali.com/api/transporteurs';
	const API_CATEGORIES	= 'http://back.kali.com/api/categories';
	const API_TOP			= 'http://back.kali.com/api/top10/produit';
	const API_PRODUIT		= 'http://back.kali.com/api/produits/';
	const API_VENTE_FLASH	= 'http://back.kali.com/api/vente/flash';
	
	/**
	 * Permet d'initialiser les données réutilisables
	 *
	 */
	public function __construct($session)
	{
		/* Récupération de la session */
		$this->_session = $session;
	
		/* Création du bundle Browser */
		$this->_browser = new Browser();
	}
	
	/**
	 * Permet de récupérer la config
	 *
	 */
	public function getConfig()
	{
		$config = $this->getDataApi(self::API_CONFIGURATION, $this->_siteName);
	
		if (array_key_exists(0, $config)) {
			throw new NotFoundHttpException(sprintf('Configuration inconnue'));
		}
		
		return $config;
	}
	
	/**
	 * Permet de récupérer les thèmes
	 *
	 */
	public function getTheme()
	{
		$config = $this->getConfig();
		$theme  = $this->getDataApi(self::API_THEME, $config['theme']['id']);
	
		if (array_key_exists(0, $theme)) {
			throw new NotFoundHttpException(sprintf('Thème inconnue'));
		}
		
		return $theme;
	}
	
	/**
	 * Permet de récupérer les transporteurs
	 *
	 * @param Browser $browser
	 * @return mixed
	 */
	public function getTransporteur()
	{
		$transporteurs = $this->getDataApi(self::API_TRANSPORTEUR);
	
		return $transporteurs;
	}
	
	/**
	 * Permet de récupérer les catégories
	 *
	 */
	public function getCategories()
	{
		$categories = $this->getDataApi(self::API_CATEGORIES);
		
		if (!$categories) {
			throw new NotFoundHttpException(sprintf('Catégories introuvable'));
		}
		
		return $categories;
	}
	
	/**
	 * Permet de récupérer le Top10
	 *
	 */
	public function getTop()
	{
		$top = $this->getDataApi(self::API_TOP);
	
		if (!$top) {
			throw new NotFoundHttpException(sprintf('Produit top 10 introuvable'));
		}
		
		return $top;
	}
	
	/**
	 * Permet de récupérer les produits
	 *
	 * @param Browser $browser
	 * @param varchar $reference
	 * @return array
	 */
	public function getProduit($reference)
	{
		$produit = $this->getDataApi(self::API_PRODUIT, $reference);
	
		return $produit;
	}
	
	/**
	 * Permet de récupérer les ventes flash
	 *
	 */
	public function getVenteFlash()
	{
		$venteFlash = $this->getDataApi(self::API_VENTE_FLASH);

		if (!$venteFlash) {
			throw new NotFoundHttpException(sprintf('Produits Vente Flash introuvable'));
		}
		
		return $venteFlash;
	}
	
	/**
	 * Appel à l'API
	 *
	 * @param Browser $browser
	 * @param string $link
	 * @param string $data
	 * @return mixed
	 */
	public function getDataApi($link, $data = null)
	{
		$reponse = ($data) ? $this->_browser->get($link . $data) : $this->_browser->get($link);
		$retour  = json_decode($reponse->getContent(), true);
	
		return $retour;
	}
}