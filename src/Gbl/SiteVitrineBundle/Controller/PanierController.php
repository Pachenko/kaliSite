<?php
namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Buzz\Browser;

class PanierController extends Controller
{
	/**
	 * @Route("/panier", name="panier.index")
	 * @Template
	 */
	public function indexAction()
	{
		$commandes = $this->get('session')->get('commandes');
		$total     = 0;
		$quantite  = 0;
		
		foreach ($commandes as $reference => $produit) {
			$total += $produit['prix'];
		}
		
		$quantite = count($commandes);
		
		return array(
			'commande' => $commandes,
			'total'    => $total,	
			'quantite' => $quantite,
		);
	}
	
	/**
	 * Permet de payer
	 * 
	 * @Route("/panier/achat", name="panier.achat")
	 * @Template();
	 */
	public function achatAction()
	{
		
		return array();
	}
	
	/**
	 * Permet de supprimer un produit de la commande
	 * 
	 * @Route("/panier/delete", name="panier.delete")
	 */
	public function deleteAction(Request $request) 
	{
			
	}
	
	/**
	 * Permet de raoujouter un produit dans le panier
	 * 
	 * @Route("/panier/add", name="panier.add")
	 */
	public function addAction(Request $request)
	{
		/* Récupération de la référence du produit et sa quantite */
		$reference  = $request->get('ref');
		$quantite   = $request->get('qte');
		$jsonRetour = new JsonResponse();
		$ajax       = 'ko';
		
		/* Récupération du produit depuis le back */
		$browser = new Browser();
		$reponse = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/produits/' . $reference);
		$produit = json_decode($reponse->getContent(), true);

		/* Ajout de la quatite pour un produit*/
		$produit['quantite'] = intval($quantite);
		
		/* Récupération de la session */
		$commandes = $this->get('session')->get('commandes');

		/* Ajout du produit dans la commande */
		if ($commandes[$produit['reference']] = $produit)
			$ajax = 'ok';
		
		/* Ajout de la commande dans la session */
		$this->get('session')->set('commandes', $commandes);
		
		/* Retour ajax */
		$jsonRetour->setData(array(
			'ajax' => $ajax,
		));

		return $jsonRetour;
	}
}