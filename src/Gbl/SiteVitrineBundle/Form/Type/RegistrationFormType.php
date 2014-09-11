<?php

namespace Gbl\SiteVitrineBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);
		$builder->add('nom');
		$builder->add('prenom');
		$builder->add('adresse');
		$builder->add('date_naissance', 'date');
		$builder->add('telephone_fixe');
		$builder->add('telephone_portable');
	}

	public function getParent()
	{
		return 'fos_user_registration';
	}

	public function getName()
	{
		return 'gbl_user_registration';
	}
}