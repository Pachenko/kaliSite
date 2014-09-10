<?php
namespace Gbl\SiteVitrineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Buzz\Browser;

class PanierController extends Controller
{
	/**
	 * @Route("/panier", name="panier.index")
	 * @Template()
	 */
	public function indexAction(Request $request)
	{
		$reference = $request->get('ref');
		$browser   = new Browser();
		$reponse   = $browser->get('http://localhost/kaliBackOffice/web/app_dev.php/api/produits/' . $reference);
		$produit   = json_decode($reponse->getContent(), true);
		
		if ($produit['stock'] === 'ok')
			$this->get('session')->set('commandes', array($produit));
		var_dump($this->get('session')->get('commandes')); die();
		return array('');
	}
}