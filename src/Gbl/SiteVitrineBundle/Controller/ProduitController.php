<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Buzz\Browser;

class ProduitController extends Controller
{
	/**
	 * @Route("/produits/{reference}", name="produits.index")
	 * @Template()
	 */
	public function indexAction($reference)
	{
		$browser   = new Browser();
		$produits  = $browser->get('http://back.kali.com/api/produits/' . $reference);
		
		$produit = json_decode($produits->getContent(), true);
		
		return array(
			'produit' => $produit,
		);
	}	
}