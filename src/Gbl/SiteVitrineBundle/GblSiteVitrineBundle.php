<?php

namespace Gbl\SiteVitrineBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GblSiteVitrineBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
