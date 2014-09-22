<?php
namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Buzz\Browser as Browser;

class PanierController extends Controller
{
	/**
	 * Session
	 *
	 * @var Session
	 */
	protected $_session = null;
	
	/**
	 * Méthode file_get_content()
	 *
	 * @var Buzz\Browser
	 */
	protected $_browser = null;
	
	/**
	 * Ecotaxe initialisation
	 * 
	 * @var double
	 */
	protected $_ecotaxe = 0.52;
	
	/**
	 * Permet d'initialiser les données réutilisables
	 */
	public function initializer()
	{
		/* Récupération de la session */
		$this->_session = $this->get('session');
		
		/* Création du bundle Browser */
		$this->_browser = new Browser();
		
		/* Initialisation des données */
		$categories   = $this->getCategories($this->_browser);
		$transporteur = $this->getTransporteur($this->_browser);
		
		/* Retourne un tableau de données */
		return array(
			'categories'   => $categories,
			'transporteur' => $transporteur,
		);
	}
	
	/**
	 * @Route("/panier", name="panier.index")
	 * @Template()
	 */
	public function indexAction()
	{
		$initializer = $this->initializer();
		$commandes   = $this->_session->get('commandes');
		$total       = 0;
		$quantite    = 0;
		
		
		if (isset($commandes)) { 
			foreach ($commandes as $reference => $produit) {
				$total += $produit['prix'] * $produit['quantite'];
			}
		}
		
		$quantite = count($commandes);
		
		return array(
			'commande' 	 => $commandes,
			'total'    	 => $total,	
			'quantite' 	 => $quantite,
			'categories' => $initializer['categories'],
		);
	}
	
	/**
	 * Permet de payer
	 * 
	 * @Route("/panier/achat", name="panier.achat")
	 * @Template()
	 */
	public function achatAction()
	{
		$initializer	   = $this->initializer();
		$commandes 		   = $this->_session->get('commandes');
		$user			   = $this->container->get('security.context')
								   			 ->getToken()
											 ->getUser();
		$quantiteProduits  = 0;
		$poidsTotal		   = 0;
		$prixTotal		   = 0;
		$prix			   = 0;
		$ecotaxe		   = $this->_ecotaxe;
		$transporteurs	   = [];
		$transporteur	   = '';
		$temp			   = [];
		$dimensionTotal	   = 0;
		$statut 		   = 'ok';
		$date			   = date('Y-m-d');
		$referenceCommande = uniqid();
		
		if (isset($commandes)) {
			foreach ($commandes as $reference => $produit) {
				$quantiteProduits += $produit['quantite'];
				$poidsTotal 	  += $produit['poids'] * $produit['quantite'];
				$prixTotal		  += $produit['prix']  * $produit['quantite'];
				$prix			  += $produit['prix']  * $produit['quantite'];
				$dimensionTotal   += $produit['dimensions'] * $produit['quantite'];
			}
		}
		
		$ecotaxe   *= $poidsTotal;
		$prixTotal += $ecotaxe;
		
		if (isset($initializer['transporteur'])) {
			foreach ($initializer['transporteur'] as $k => $nom) {
				$temp[] = $nom['nom'];
			}
		}
		
		$transporteur = ($poidsTotal >= 1.5 && $dimensionTotal > 60) ? $temp[1] : $temp[0];
	
		$this->_session->set('prix', $prixTotal);
		$this->_session->set('transporteur', $transporteur);
		$this->_session->set('poids', $poidsTotal);
		
		return array(
			'commandes' 	    => $commandes,
			'quantiteProduit'   => $quantiteProduits,	
			'poidsTotal'	    => $poidsTotal,
			'ecotaxe'		    => $ecotaxe,
			'prixTotal'		    => $prixTotal,	
			'prix'			    => $prix,
			'transporteur'	    => $transporteur,	
			'user'			    => $user,
			'categories' 	    => $initializer['categories'],
			'referenceCommande' => $referenceCommande,
			'statut'			=> $statut,
			'date'				=> $date,
			'reference'			=> $referenceCommande,
		);
	}
	
	/**
	 * Permet de supprimer un produit de la commande
	 * 
	 * @Route("/panier/confirm", name="panier.confirm")
	 * @Template()
	 */
	public function confirmAction(Request $request) 
	{
		$initializer	   = $this->initializer();
		$commandes    	   = $this->_session->get('commandes');
		$prix	       	   = $this->_session->get('prix');
		$transporteur 	   = $this->_session->get('transporteur');
		$poids 		  	   = $this->_session->get('poids');
		
		/* On met a jour les stocks des produits */
		//TO DO
		
		/* On vide la session, la commande est confirm�e */
		$this->_session->remove('commandes');
		
		return array(
			'prix'	       => $prix,
			'transporteur' => $transporteur,
			'poids'	       => $poids,
			'categories'   => $initializer['categories'],
		);
	}
	
	/**
	 * Permet de raoujouter un produit dans le panier
	 * 
	 * @Route("/panier/add", name="panier.add")
	 */
	public function addAction(Request $request)
	{
		$initializer = $this->initializer();
		
		/* Recuperation de la reference du produit et sa quantite */
		$reference  = $request->get('ref');
		$quantite   = $request->get('qte');
		
		$jsonRetour = new JsonResponse();
		$ajax       = 'ko';
		
		/* Récupération de l'utilisateur */
		$user= $this->container->get('security.context')
					->getToken()
					->getUser();
		
		/* Récupération des produits dans le back */
		$produit = $this->getProduit($this->_browser, $reference);

		/* Ajout de la quatite pour un produit*/
		$produit['quantite'] = intval($quantite);
		
		/* Récupération de la session */
		$commandes = $this->_session->get('commandes');

		/* Ajout du produit dans la commande */
		if ($user !== 'anon.') {
			$commandes[$produit['reference']] = $produit;
			$ajax = 'ok';
		}
				
		/* Ajout de la commande dans la session */
		$this->_session->set('commandes', $commandes);
		$this->_session->set('panier', count($commandes));
		
		/* Retour ajax */
		$jsonRetour->setData(array(
			'ajax' => $ajax,
		));

		return $jsonRetour;
	}
	
	/**
	 * Permet de récupérer les catégories
	 * 
	 * @param Browser $browser
	 * @throws NotFoundHttpException
	 * @return mixed
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
	 * Permet de récupérer les transporteurs
	 * 
	 * @param Browser $browser
	 * @return mixed
	 */
	public function getTransporteur(Browser $browser)
	{
		$reponse 	   = $browser->get('http://back.kali.com/api/transporteurs');
		$transporteurs = json_decode($reponse->getContent(), true);
	
		return $transporteurs;
	}
	
	/**
	 * Permet de récupérer les produits
	 * 
	 * @param Browser $browser
	 * @param varchar $reference
	 * @return array
	 */
	public function getProduit(Browser $browser, $reference)
	{
		$reponse = $browser->get('http://back.kali.com/api/produits/' . $reference);
		$produit = json_decode($reponse->getContent(), true);
		
		return $produit;
	}
}