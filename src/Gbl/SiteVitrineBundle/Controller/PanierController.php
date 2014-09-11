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
	 */
	public function indexAction(Request $request)
	{

	}
	
	/**
	 * Permet de raoujouter un produit dans le panier
	 * 
	 * @Route("/panier/add", name="panier.add")
	 */
	public function addAction(Request $request)
	{
		/* Récupération de la référence du produit */
		$reference  = $request->get('ref');
		$jsonRetour = new JsonResponse();
		$ajax       = 'ko';
		
		/* Récupération du produit depuis le back */
		$browser   = new Browser();
		$reponse   = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/produits/' . $reference);
		$produit   = json_decode($reponse->getContent(), true);
		
		/* Récupération de la session */
		$commandes = $this->get('session')->get('commandes');
		if ($produit = $commandes[$produit['reference']])
			$ajax = 'ok';
		
		/* Ajout de la commande dans la session */
		$this->get('session')->set('commandes', $commandes);
		
		/* Retour ajax */
		$jsonRetour->setData(array(
			'reponse' => $ajax,
		));
		
		return $jsonRetour;
		
	}
}