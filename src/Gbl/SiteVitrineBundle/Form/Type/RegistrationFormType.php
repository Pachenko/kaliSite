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
		$builder->add('codepostal');
		$builder->add('ville');
		$builder->add('pays');
		$builder->add('date_naissance', 'date', array(
            'format' => 'dd-MMMM-yyyy',
            'years' =>  range(\date("Y") - 0, \date("Y") - 100),
        ));
		$builder->add('telephone_fixe');
		$builder->add('telephone_portable');

		$builder->add('Retour', 'button', array(
				'attr' => array('onClick' => 'javascript:history.go(-1)'),
		));
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