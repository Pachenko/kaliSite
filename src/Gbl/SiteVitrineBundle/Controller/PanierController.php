<?php
namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Buzz\Browser;

class PanierController extends Controller
{
	/**
	 * 
	 * 
	 * @Route("/panier", name="panier.index")
	 */
	public function indexAction()
	{
		$commandes = $this->get('session')->get('commandes');
		$total     = 0;
		$quantite  = 0;
		
		
		if (isset($commandes)) { 
			foreach ($commandes as $reference => $produit) {
				$total += $produit['prix'] * $produit['quantite'];
			}
		}
		$quantite = count($commandes);
		
		////////////////////////////////
		//	  API pour catégories	  //
		////////////////////////////////
		$browser = new Browser();
		
		$categories = $browser->get('http://back.kali.com/api/categories');
		
		//Tableau des infos config
		$infoCat = json_decode($categories->getContent(), true);
		
		if (!$infoCat) {
			throw new NotFoundHttpException(sprintf('Catégories introuvable'));
		}
		
		return $this->render('GblSiteVitrineBundle:Panier:index.html.twig', array(
			'commande' 	 => $commandes,
			'total'    	 => $total,	
			'quantite' 	 => $quantite,
			'categories' => $infoCat,
		));
	}
	
	/**
	 * Permet de payer
	 * 
	 * @Route("/panier/achat", name="panier.achat")
	 * @Template()
	 */
	public function achatAction()
	{
		$commandes 		   = $this->get('session')->get('commandes');
		$user			   = $this->container->get('security.context')
								   			 ->getToken()
											 ->getUser();
		$quantiteProduits  = 0;
		$poidsTotal		   = 0;
		$prixTotal		   = 0;
		$prix			   = 0;
		$ecotaxe		   = 0.52;
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
		
		$browser 	   = new Browser();
		$reponse 	   = $browser->get('http://back.kali.com/api/transporteurs');
		$transporteurs = json_decode($reponse->getContent(), true);
		
		
		if (isset($transporteurs)) {
			foreach ($transporteurs as $k => $nom) {
				$temp[] = $nom['nom'];
			}
		}
		
		$transporteur = ($poidsTotal >= 1.5 && $dimensionTotal > 60) ? $temp[1] : $temp[0];
		
		$session = $this->get('session');
		$session->set('prix', $prixTotal);
		$session->set('transporteur', $transporteur);
		$session->set('poids', $poidsTotal);
		
		////////////////////////////////
		//	  API pour catégories	  //
		////////////////////////////////
		$browser = new Browser();
		
		$categories = $browser->get('http://back.kali.com/api/categories');
		
		//Tableau des infos config
		$infoCat = json_decode($categories->getContent(), true);
		
		if (!$infoCat) {
			throw new NotFoundHttpException(sprintf('Catégories introuvable'));
		}
		
		return array(
			'commandes' 	    => $commandes,
			'quantiteProduit'   => $quantiteProduits,	
			'poidsTotal'	    => $poidsTotal,
			'ecotaxe'		    => $ecotaxe,
			'prixTotal'		    => $prixTotal,	
			'prix'			    => $prix,
			'transporteur'	    => $transporteur,	
			'user'			    => $user,
			'categories' 	    => $infoCat,
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
		$session	  	   = $this->get('session');
		$commandes    	   = $session->get('commandes');
		$prix	       	   = $session->get('prix');
		$transporteur 	   = $session->get('transporteur');
		$poids 		  	   = $session->get('poids');
		
		/* On met a jour les stocks des produits */
		
		
		/* On vide la session, la commande est confirm�e */
		$session->remove('commandes');
		
		$browser = new Browser();
		
		$categories = $browser->get('http://back.kali.com/api/categories');
		
		//Tableau des infos config
		$infoCat = json_decode($categories->getContent(), true);
		
		if (!$infoCat) {
			throw new NotFoundHttpException(sprintf('Catégories introuvable'));
		}
		
		return array(
			'prix'	       		=> $prix,
			'transporteur' 		=> $transporteur,
			'poids'	       		=> $poids,
			'categories'   		=> $infoCat,
		);
	}
	
	/**
	 * Permet de raoujouter un produit dans le panier
	 * 
	 * @Route("/panier/add", name="panier.add")
	 */
	public function addAction(Request $request)
	{
		/* Recuperation de la reference du produit et sa quantite */
		$reference  = $request->get('ref');
		$quantite   = $request->get('qte');
		$jsonRetour = new JsonResponse();
		$ajax       = 'ko';
		
		/* Récupération de l'utilisateur */
		$user= $this->container->get('security.context')
					->getToken()
					->getUser();
		
		/* R�cup�ration du produit depuis le back */
		$browser = new Browser();
		$reponse = $browser->get('http://back.kali.com/api/produits/' . $reference);
		$produit = json_decode($reponse->getContent(), true);

		/* Ajout de la quatite pour un produit*/
		$produit['quantite'] = intval($quantite);
		
		/* R�cup�ration de la session */
		$commandes = $this->get('session')->get('commandes');

		/* Ajout du produit dans la commande */
		if ($user !== 'anon.') {
			$commandes[$produit['reference']] = $produit;
			$ajax = 'ok';
		}
				
		/* Ajout de la commande dans la session */
		$this->get('session')->set('commandes', $commandes);
		$this->get('session')->set('panier', count($commandes));
		
		/* Retour ajax */
		$jsonRetour->setData(array(
			'ajax' => $ajax,
		));

		return $jsonRetour;
	}
}