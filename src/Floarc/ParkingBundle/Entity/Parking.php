<?php

namespace Floarc\ParkingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Floarc\UserBundle\Entity\User as User;


/**
 * Parking
 *
 * @ORM\Table(name="parking", indexes={@ORM\Index(name="fk_parking_type_place", columns={"id_type_place"}), @ORM\Index(name="fk_parking_type_contrat", columns={"id_type_contrat"}), @ORM\Index(name="fk_parking_type_duree", columns={"id_type_duree_mini"}), @ORM\Index(name="fk_parking_type_annonce", columns={"id_type_annonce"}), @ORM\Index(name="fk_parking_address", columns={"id_address"}), @ORM\Index(name="fk_parking_user", columns={"id_user"})})
 * @ORM\Entity(repositoryClass="Floarc\ParkingBundle\Entity\ParkingRepository")
 */
class Parking
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_mois", type="string", length=45, nullable=true)
     */
    private $prixMois;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_semaine", type="string", length=45, nullable=true)
     */
    private $prixSemaine;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_journee", type="string", length=45, nullable=true)
     */
    private $prixJournee;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_vente", type="string", length=45, nullable=true)
     */
    private $prixVente;

    /**
     * @var integer
     *
     * @ORM\Column(name="capacite", type="integer", nullable=true)
     */
    private $capacite;

    /**
     * @var integer
     *
     * @ORM\Column(name="duree_mini", type="integer", nullable=true)
     */
    private $dureeMini;

    /**
     * @var boolean
     *
     * @ORM\Column(name="prix_negociable", type="boolean", nullable=true)
     */
    private $prixNegociable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="acces24", type="boolean", nullable=true)
     */
    private $acces24;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ferme_clef", type="boolean", nullable=true)
     */
    private $fermeClef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="camera", type="boolean", nullable=true)
     */
    private $camera;

    /**
     * @var boolean
     *
     * @ORM\Column(name="garde", type="boolean", nullable=true)
     */
    private $garde;

    /**
     * @var boolean
     *
     * @ORM\Column(name="underground", type="boolean", nullable=true)
     */
    private $underground;

    /**
     * @var boolean
     *
     * @ORM\Column(name="eclaire_nuit", type="boolean", nullable=true)
     */
    private $eclaireNuit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="abrite", type="boolean", nullable=true)
     */
    private $abrite;

    /**
     * @var boolean
     *
     * @ORM\Column(name="acces_handicape", type="boolean", nullable=true)
     */
    private $accesHandicape;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_approved", type="boolean", nullable=true)
     */
    private $isApproved;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \TypePlace
     *
     * @ORM\ManyToOne(targetEntity="TypePlace")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_place", referencedColumnName="id")
     * })
     */
    private $idTypePlace;

    /**
     * @var \TypeContrat
     *
     * @ORM\ManyToOne(targetEntity="TypeContrat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_contrat", referencedColumnName="id")
     * })
     */
    private $idTypeContrat;

    /**
     * @var \TypeDuree
     *
     * @ORM\ManyToOne(targetEntity="TypeDuree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_duree_mini", referencedColumnName="id")
     * })
     */
    private $idTypeDureeMini;

    /**
     * @var \TypeAnnonce
     *
     * @ORM\ManyToOne(targetEntity="TypeAnnonce")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_annonce", referencedColumnName="id")
     * })
     */
    private $idTypeAnnonce;
    
    /**
     * @var \Address
     *
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_address", referencedColumnName="id")
     * })
     */
    private $idAddress;
    

    /**
     * @var \Floarc\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Floarc\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;    



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
     * Set title
     *
     * @param string $title
     * @return Parking
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set prixMois
     *
     * @param string $prixMois
     * @return Parking
     */
    public function setPrixMois($prixMois)
    {
        $this->prixMois = $prixMois;

        return $this;
    }

    /**
     * Get prixMois
     *
     * @return string 
     */
    public function getPrixMois()
    {
        return $this->prixMois;
    }

    /**
     * Set prixSemaine
     *
     * @param string $prixSemaine
     * @return Parking
     */
    public function setPrixSemaine($prixSemaine)
    {
        $this->prixSemaine = $prixSemaine;

        return $this;
    }

    /**
     * Get prixSemaine
     *
     * @return string 
     */
    public function getPrixSemaine()
    {
        return $this->prixSemaine;
    }

    /**
     * Set prixJournee
     *
     * @param string $prixJournee
     * @return Parking
     */
    public function setPrixJournee($prixJournee)
    {
        $this->prixJournee = $prixJournee;

        return $this;
    }

    /**
     * Get prixJournee
     *
     * @return string 
     */
    public function getPrixJournee()
    {
        return $this->prixJournee;
    }

    /**
     * Set prixVente
     *
     * @param string $prixVente
     * @return Parking
     */
    public function setPrixVente($prixVente)
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    /**
     * Get prixVente
     *
     * @return string 
     */
    public function getPrixVente()
    {
        return $this->prixVente;
    }

    /**
     * Set capacite
     *
     * @param integer $capacite
     * @return Parking
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * Get capacite
     *
     * @return integer 
     */
    public function getCapacite()
    {
        return $this->capacite;
    }

    /**
     * Set dureeMini
     *
     * @param integer $dureeMini
     * @return Parking
     */
    public function setDureeMini($dureeMini)
    {
        $this->dureeMini = $dureeMini;

        return $this;
    }

    /**
     * Get dureeMini
     *
     * @return integer 
     */
    public function getDureeMini()
    {
        return $this->dureeMini;
    }

    /**
     * Set prixNegociable
     *
     * @param boolean $prixNegociable
     * @return Parking
     */
    public function setPrixNegociable($prixNegociable)
    {
        $this->prixNegociable = $prixNegociable;

        return $this;
    }

    /**
     * Get prixNegociable
     *
     * @return boolean 
     */
    public function getPrixNegociable()
    {
        return $this->prixNegociable;
    }

    /**
     * Set acces24
     *
     * @param boolean $acces24
     * @return Parking
     */
    public function setAcces24($acces24)
    {
        $this->acces24 = $acces24;

        return $this;
    }

    /**
     * Get acces24
     *
     * @return boolean 
     */
    public function getAcces24()
    {
        return $this->acces24;
    }

    /**
     * Set fermeClef
     *
     * @param boolean $fermeClef
     * @return Parking
     */
    public function setFermeClef($fermeClef)
    {
        $this->fermeClef = $fermeClef;

        return $this;
    }

    /**
     * Get fermeClef
     *
     * @return boolean 
     */
    public function getFermeClef()
    {
        return $this->fermeClef;
    }

    /**
     * Set camera
     *
     * @param boolean $camera
     * @return Parking
     */
    public function setCamera($camera)
    {
        $this->camera = $camera;

        return $this;
    }

    /**
     * Get camera
     *
     * @return boolean 
     */
    public function getCamera()
    {
        return $this->camera;
    }

    /**
     * Set garde
     *
     * @param boolean $garde
     * @return Parking
     */
    public function setGarde($garde)
    {
        $this->garde = $garde;

        return $this;
    }

    /**
     * Get garde
     *
     * @return boolean 
     */
    public function getGarde()
    {
        return $this->garde;
    }

    /**
     * Set underground
     *
     * @param boolean $underground
     * @return Parking
     */
    public function setUnderground($underground)
    {
        $this->underground = $underground;

        return $this;
    }

    /**
     * Get underground
     *
     * @return boolean 
     */
    public function getUnderground()
    {
        return $this->underground;
    }

    /**
     * Set eclaireNuit
     *
     * @param boolean $eclaireNuit
     * @return Parking
     */
    public function setEclaireNuit($eclaireNuit)
    {
        $this->eclaireNuit = $eclaireNuit;

        return $this;
    }

    /**
     * Get eclaireNuit
     *
     * @return boolean 
     */
    public function getEclaireNuit()
    {
        return $this->eclaireNuit;
    }

    /**
     * Set abrite
     *
     * @param boolean $abrite
     * @return Parking
     */
    public function setAbrite($abrite)
    {
        $this->abrite = $abrite;

        return $this;
    }

    /**
     * Get abrite
     *
     * @return boolean 
     */
    public function getAbrite()
    {
        return $this->abrite;
    }

    /**
     * Set accesHandicape
     *
     * @param boolean $accesHandicape
     * @return Parking
     */
    public function setAccesHandicape($accesHandicape)
    {
        $this->accesHandicape = $accesHandicape;

        return $this;
    }

    /**
     * Get accesHandicape
     *
     * @return boolean 
     */
    public function getAccesHandicape()
    {
        return $this->accesHandicape;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     * @return Parking
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * Get isApproved
     *
     * @return boolean 
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }
    

    /**
     * Set status
     *
     * @param integer $status
     * @return Parking
     */
    public function setStatus($status)
    {
    	$this->status = $status;
    
    	return $this;
    }
    
    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
    	return $this->status;
    }    

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Parking
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Parking
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set idTypePlace
     *
     * @param \Floarc\ParkingBundle\Entity\TypePlace $idTypePlace
     * @return Parking
     */
    public function setIdTypePlace(\Floarc\ParkingBundle\Entity\TypePlace $idTypePlace = null)
    {
        $this->idTypePlace = $idTypePlace;

        return $this;
    }

    /**
     * Get idTypePlace
     *
     * @return \Floarc\ParkingBundle\Entity\TypePlace 
     */
    public function getIdTypePlace()
    {
        return $this->idTypePlace;
    }

    /**
     * Set idTypeContrat
     *
     * @param \Floarc\ParkingBundle\Entity\TypeContrat $idTypeContrat
     * @return Parking
     */
    public function setIdTypeContrat(\Floarc\ParkingBundle\Entity\TypeContrat $idTypeContrat = null)
    {
        $this->idTypeContrat = $idTypeContrat;

        return $this;
    }

    /**
     * Get idTypeContrat
     *
     * @return \Floarc\ParkingBundle\Entity\TypeContrat 
     */
    public function getIdTypeContrat()
    {
        return $this->idTypeContrat;
    }
    
    /**
     * Set idTypeAnnonce
     *
     * @param \Floarc\ParkingBundle\Entity\TypeAnnonce $idTypeAnnonce
     * @return Parking
     */
    public function setIdTypeAnnonce(\Floarc\ParkingBundle\Entity\TypeAnnonce $idTypeAnnonce = null)
    {
    	$this->idTypeAnnonce = $idTypeAnnonce;
    
    	return $this;
    }
    
    /**
     * Get idTypeAnnonce
     *
     * @return \Floarc\ParkingBundle\Entity\TypeAnnonce
     */
    public function getIdTypeAnnonce()
    {
    	return $this->idTypeAnnonce;
    }    
    

    /**
     * Set idTypeDureeMini
     *
     * @param \Floarc\ParkingBundle\Entity\TypeDuree $idTypeDureeMini
     * @return Parking
     */
    public function setIdTypeDureeMini(\Floarc\ParkingBundle\Entity\TypeDuree $idTypeDureeMini = null)
    {
        $this->idTypeDureeMini = $idTypeDureeMini;

        return $this;
    }

    /**
     * Get idTypeDureeMini
     *
     * @return \Floarc\ParkingBundle\Entity\TypeDuree 
     */
    public function getIdTypeDureeMini()
    {
        return $this->idTypeDureeMini;
    }

    /**
     * Set idAddress
     *
     * @param \Floarc\ParkingBundle\Entity\Address $idAddress
     * @return Parking
     */
    public function setIdAddress(\Floarc\ParkingBundle\Entity\Address $idAddress = null)
    {
        $this->idAddress = $idAddress;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \Floarc\UserBundle\Entity\User 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
    
    /**
     * Set idUser
     *
     * @param \Floarc\UserBundle\Entity\User $idUser
     * @return \Floarc\UserBundle\Entity\User
     */
    public function setIdUser(\Floarc\UserBundle\Entity\User $idUser = null)
    {
    	$this->idUser = $idUser;
    
    	return $this;
    }
    
    /**
     * Get idAddress
     *
     * @return \Floarc\ParkingBundle\Entity\Address
     */
    public function getIdAddress()
    {
    	return $this->idAddress;
    }    
    
    /**
     * Return the location for Elasticsearch
     *
     * @return array
     */
    public function getLocation()
    {
    	$location = array();
    	$location["lat"] = $this->getIdAddress()->getLat();
    	$location["lon"] = $this->getIdAddress()->getLng();
    
    	return $location;
    }    
    
}
