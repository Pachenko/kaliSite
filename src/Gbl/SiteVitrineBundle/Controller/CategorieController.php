<?php

namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Buzz\Browser;

class CategorieController extends Controller
{
	/**
	 * @Route("/categories/{name}", name="categories.index")
	 * @Template()
	 */
	public function indexAction($name)
	{
		$browser    = new Browser();
		$produits   = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/products/' . $name);
		$categorie  = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/categories');
		
		$productByCategorie = json_decode($produits->getContent(), true);
		$categories 		= json_decode($categorie->getContent(), true);
		//var_dump($this->get('session')->get('commandes')); die();
		return array(
			'produits' 	 => $productByCategorie,
			'categories' => $categories	
		);
	}	
}