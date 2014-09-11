<?php

namespace Gbl\SiteVitrineBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Translation\Tests\String;
use Symfony\Component\Validator\Constraints\Date;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 * 
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Gbl\SiteVitrineBundle\Repository\UserRepository")
 * @ORM\Table(name="utilisateur")
 * 
 */
class User extends BaseUser
{
	/**
	 * @var integer
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @var String
	 * 
	 * @ORM\Column(name="nom", type="string", length=255, nullable=true)
	 * @Assert\NotBlank(message="Merci de rentrer votre nom", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max="20",
     *     minMessage="Le nom est trop court",
     *     maxMessage="Le nom est trop long",
     *     groups={"Registration", "Profile"}
     * )
	 */
	protected $nom;
	
	/**
	 * @var String
	 *
	 * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
	 */
	protected $prenom;
	
	/**
	 * @var String
	 *
	 * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
	 */
	protected $adresse;
	
	/**
	 * @var String
	 *
	 * @ORM\Column(name="ville", type="string", length=255, nullable=true)
	 * 
	 */
	protected $ville;
	
	/**
	 * @var String
	 *
	 * @ORM\Column(name="code_postal", type="integer", length=5, nullable=true)
	 * 
	 */
	protected $codePostal;
	
	/**
	 * @var String
	 *
	 * @ORM\Column(name="pays", type="string", length=255, nullable=true)
	 * 
	 */
	protected $pays;
	
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date_naissance", type="date")
	 * 
	 */
	protected $dateNaissance;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="telephone_fixe", type="integer", length=11, nullable=true)
	 * 
	 */
	protected $telephoneFixe;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="telephone_portable", type="integer", length=11, nullable=true)
	 * 
	 */
	protected $telephonePortable;

	public function __construct()
	{
		parent::__construct();
		$this->dateNaissance = new \DateTime();
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return User
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     * @return User
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set pays
     *
     * @param string $pays
     * @return User
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string 
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set telephoneFixe
     *
     * @param integer $telephoneFixe
     * @return User
     */
    public function setTelephoneFixe($telephoneFixe)
    {
        $this->telephoneFixe = $telephoneFixe;

        return $this;
    }

    /**
     * Get telephoneFixe
     *
     * @return integer 
     */
    public function getTelephoneFixe()
    {
        return $this->telephoneFixe;
    }

    /**
     * Set telephonePortable
     *
     * @param integer $telephonePortable
     * @return User
     */
    public function setTelephonePortable($telephonePortable)
    {
        $this->telephonePortable = $telephonePortable;

        return $this;
    }

    /**
     * Get telephonePortable
     *
     * @return integer 
     */
    public function getTelephonePortable()
    {
        return $this->telephonePortable;
    }
}
