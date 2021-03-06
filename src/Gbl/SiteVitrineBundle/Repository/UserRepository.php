<?php

namespace Gbl\SiteVitrineBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CommandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	public function getUser($data)
	{
		$query = $this->_em->createQuery(
				'SELECT u
			    FROM GblBackOfficeBundle:User u
			    WHERE u.nom like :nom
				or u.prenom like :prenom
				or u.username like :username
				or u.email like :email
				or u.ville like :ville
				or u.codePostal like :codePostal
				or u.telephoneFixe like :telephoneFixe
			    
	')->setParameter('nom', $data->getNom())
	->setParameter('prenom', $data->getPrenom())
	->setParameter('username', $data->getUsername())
	->setParameter('email', $data->getEmail())
	->setParameter('ville', $data->getVille())
	->setParameter('codePostal', $data->getCodePostal())
	->setParameter('telephoneFixe', $data->getTelephoneFixe());
		
		$users = $query->getResult();
		
		return $users;
	}
	
	public function getClients()
	{
		$query = $this->_em->createQuery(
				'SELECT u
			    FROM GblBackOfficeBundle:User u
			    WHERE u.roles = :roles
			 ')->setParameter('roles', 'a:0:{}');
			
		$users = $query->getResult();
	
		return $users;
	}
}