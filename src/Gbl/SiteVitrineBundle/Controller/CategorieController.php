<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Buzz\Browser;

class CategorieController extends Controller
{
	/**
	 * Liste de tous les produits d'une catégorie spécifique
	 * 
	 * @Route("/categories/{id}", name="categories.index")
	 * @Template()
	 */
	public function indexAction($id)
	{
		$browser    = new Browser();
		$produits   = $browser->get('http://back.kali.com/api/products/' . $id);
		$categorie  = $browser->get('http://back.kali.com/api/categories');
		
		$productByCategorie = json_decode($produits->getContent(), true);
		$categories 		= json_decode($categorie->getContent(), true);

		return array(
			'produits' 	 => $productByCategorie,
			'categories' => $categories	
		);
	}	
}