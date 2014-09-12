<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Buzz\Browser;

class ProduitController extends Controller
{
	/**
	 * @Route("/produits/{reference}", name="produits.index")
	 */
	public function indexAction($reference)
	{
		$browser   = new Browser();
		$produits  = $browser->get('http://back.kali.com/api/produits/' . $reference);
		
		$produit = json_decode($produits->getContent(), true);
		
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
		
		return $this->render('GblSiteVitrineBundle:Produit:index.html.twig', array(
				'produit' => $produit,
				'categories' => $infoCat,
		));
	}	
}