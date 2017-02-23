<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Strain.
 *
 * @ORM\Table(name="strain")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StrainRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Strain
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="discriminator", type="string", length=255)
     */
    private $discriminator;

    /**
     * @var string
     *
     * @ORM\Column(name="auto_name", type="string", length=255, unique=true)
     */
    private $autoName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var bool
     *
     * @ORM\Column(name="sequenced", type="boolean")
     */
    private $sequenced;

    /**
     * @var Species
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Species", inversedBy="strains")
     * @ORM\JoinColumn(name="species", nullable=false)
     */
    private $species;

    /**
     * @var Type
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Type", inversedBy="strains")
     * @ORM\JoinColumn(name="type")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="genotype", type="text", nullable=true)
     */
    private $genotype;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection|Tube
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Tube", mappedBy="strain", cascade={"persist", "remove"})
     */
    private $tubes;

    /**
     * @var ArrayCollection|StrainPlasmid
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\StrainPlasmid", mappedBy="strain", cascade={"persist", "remove"})
     */
    private $strainPlasmids;

    /**
     * @var ArrayCollection|Strain
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Strain", inversedBy="children")
     * @ORM\JoinTable(name="strains_parents")
     */
    private $parents;

    /**
     * @var ArrayCollection|Strain
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Strain", mappedBy="parents")
     */
    private $children;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var BiologicalOriginCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BiologicalOriginCategory", inversedBy="strains")
     * @ORM\JoinColumn(name="category", nullable=true)
     */
    private $biologicalOriginCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="biologicalOrigin", type="string", length=255, nullable=true)
     */
    private $biologicalOrigin;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deletionDate", type="datetime", nullable=true)
     */
    private $deletionDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="strains")
     * @ORM\JoinColumn(name="author")
     */
    private $author;


    public function __construct()
    {
        $this->tubes = new ArrayCollection();
        $this->strainPlasmids = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->creationDate = new \DateTime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set discriminator.
     *
     * @param string $discriminator
     *
     * @return $this
     */
    public function setDiscriminator(string $discriminator)
    {
        $this->discriminator = $discriminator;

        return $this;
    }

    /**
     * Get discriminator.
     *
     * @return string
     */
    public function getDiscriminator()
    {
        return $this->discriminator;
    }

    /**
     * Set autoName.
     *
     * @param string $autoName
     *
     * @return Strain
     */
    public function setAutoName($autoName)
    {
        $this->autoName = $autoName;

        return $this;
    }

    /**
     * Get autoName.
     *
     * @return string
     */
    public function getAutoName()
    {
        return $this->autoName;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Strain
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->autoName.' - '.$this->name;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return Strain
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set sequenced.
     *
     * @param bool $sequenced
     *
     * @return Strain
     */
    public function setSequenced($sequenced)
    {
        $this->sequenced = $sequenced;

        return $this;
    }

    /**
     * Get sequenced.
     *
     * @return bool
     */
    public function getSequenced()
    {
        return $this->sequenced;
    }

    /**
     * Set species.
     *
     * @param Species $species
     *
     * @return $this
     */
    public function setSpecies(Species $species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species.
     *
     * @return Species
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set type.
     *
     * @param Type $type
     *
     * @return Strain
     */
    public function setType(Type $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set genotype.
     *
     * @param string $genotype
     *
     * @return Strain
     */
    public function setGenotype($genotype)
    {
        $this->genotype = $genotype;

        return $this;
    }

    /**
     * Get genotype.
     *
     * @return string
     */
    public function getGenotype()
    {
        return $this->genotype;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Strain
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add tube.
     *
     * @param Tube $tube
     *
     * @return Strain
     */
    public function addTube(Tube $tube)
    {
        $tube->setStrain($this);
        $this->tubes->add($tube);

        return $this;
    }

    /**
     * Remove tube.
     *
     * @param Tube $tube
     *
     * @return $this
     */
    public function removeTube(Tube $tube)
    {
        $this->tubes->removeElement($tube);

        return $this;
    }

    /**
     * Get tubes.
     *
     * @return Tube|ArrayCollection
     */
    public function getTubes()
    {
        return $this->tubes;
    }

    /**
     * Get the teams.
     *
     * @return array
     */
    public function getTeams()
    {
        $teams = [];

        foreach ($this->getTubes() as $tube) {
            $team = $tube->getBox()->getProject()->getTeam();

            if (!in_array($team, $teams)) {
                $teams[] = $team;
            }
        }

        return $teams;
    }

    /**
     * Get the projects.
     *
     * @return array
     */
    public function getProjects()
    {
        $projects = [];

        foreach ($this->getTubes() as $tube) {
            if (!in_array($project = $tube->getBox()->getProject(), $projects)) {
                $projects[] = $project;
            }
        }

        return $projects;
    }

    /**
     * Get allowed users.
     *
     * @return array
     */
    public function getAllowedUsers()
    {
        $allowedUsers = [];

        foreach ($this->getTubes() as $tube) {
            foreach ($tube->getBox()->getProject()->getMembers() as $member) {
                if (!in_array($member, $allowedUsers)) {
                    $allowedUsers[] = $member;
                }
            }
        }

        return $allowedUsers;
    }

    /**
     * Is allowed user ?
     *
     * @param User $user
     *
     * @return bool
     */
    public function isAllowedUser(User $user)
    {
        return in_array($user, $this->getAllowedUsers());
    }

    /**
     * Add strainPlasmid.
     *
     * @param StrainPlasmid $strainPlasmid
     *
     * @return $this
     */
    public function addStrainPlasmid(StrainPlasmid $strainPlasmid)
    {
        $strainPlasmid->setStrain($this);
        $this->strainPlasmids->add($strainPlasmid);

        return $this;
    }

    /**
     * Remove strainPlasmid.
     *
     * @param StrainPlasmid $strainPlasmid
     *
     * @return $this
     */
    public function removeStrainPlasmid(StrainPlasmid $strainPlasmid)
    {
        $this->strainPlasmids->removeElement($strainPlasmid);

        return $this;
    }

    /**
     * Get strainPlasmids.
     *
     * @return StrainPlasmid|ArrayCollection
     */
    public function getStrainPlasmids()
    {
        return $this->strainPlasmids;
    }

    /**
     * Add parent.
     *
     * @param Strain $strain
     *
     * @return Strain
     */
    public function addParent(Strain $strain)
    {
        $this->parents->add($strain);

        return $this;
    }

    /**
     * Remove parent.
     *
     * @param Strain $strain
     *
     * @return Strain
     */
    public function removeParent(Strain $strain)
    {
        $this->parents->removeElement($strain);

        return $this;
    }

    /**
     * Get parent.
     *
     * @return Strain|ArrayCollection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Get children.
     *
     * @return Strain|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Strain
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Strain
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set Biological origin category.
     *
     * @param $category
     *
     * @return $this
     */
    public function setBiologicalOriginCategory($category)
    {
        $this->biologicalOriginCategory = $category;

        return $this;
    }

    /**
     * Get biological origin category.
     *
     * @return BiologicalOriginCategory
     */
    public function getBiologicalOriginCategory()
    {
        return $this->biologicalOriginCategory;
    }

    /**
     * Set biologicalOrigin.
     *
     * @param string $biologicalOrigin
     *
     * @return Strain
     */
    public function setBiologicalOrigin($biologicalOrigin)
    {
        $this->biologicalOrigin = $biologicalOrigin;

        return $this;
    }

    /**
     * Get biologicalOrigin.
     *
     * @return string
     */
    public function getBiologicalOrigin()
    {
        return $this->biologicalOrigin;
    }

    /**
     * Set source.
     *
     * @param string $source
     *
     * @return Strain
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set latitude.
     *
     * @param $latitude
     *
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude.
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude.
     *
     * @param $longitude
     *
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set creationDate.
     *
     * @param \DateTime $creationDate
     *
     * @return Strain
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate.
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set deleted.
     *
     * @param bool $deleted
     *
     * @return Strain
     */
    public function setDeleted(bool $deleted)
    {
        if (true === $deleted && false === $this->deleted) {
            $this->deletionDate = new \DateTime();
        } elseif (false === $deleted && true === $this->deleted) {
            $this->deletionDate = null;
        }

        // If user delete a strain, wee need to delete all tubes
        if (true === $deleted) {
            foreach ($this->getTubes() as $tube) {
                $tube->setDeleted(true);
            }
        }

        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted.
     *
     * @return bool
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deletionDate.
     *
     * @param \DateTime $deletionDate
     *
     * @return Strain
     */
    public function setDeletionDate($deletionDate)
    {
        $this->deletionDate = $deletionDate;

        return $this;
    }

    /**
     * Get deletionDate.
     *
     * @return \DateTime
     */
    public function getDeletionDate()
    {
        return $this->deletionDate;
    }

    /**
     * Set author.
     *
     * @param User $user
     *
     * @return $this
     */
    public function setAuthor(User $user)
    {
        $this->author = $user;

        return $this;
    }

    /**
     * Get author.
     *
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Is author ?
     *
     * @param User $user
     *
     * @return bool
     */
    public function isAuthor(User $user)
    {
        return $user === $this->getAuthor();
    }

    /**
     * Set main Species.
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function lifeCycleMainSpecies()
    {
        // Define the main Species
        $species = $this->getSpecies();

        if (!$species->isMainSpecies()) {
            $this->setSpecies($species->getMainSpecies());
        }
    }

    /**
     * Set autoName.
     *
     * @ORM\PrePersist()
     */
    public function plifeCycleAutoName()
    {
        $project = $this->getTubes()->first()->getProject();
        $projectStrainNumber = $project->getLastStrainNumber() + 1;
        $projectPrefix = $project->getPrefix();

        $autoName = $projectPrefix.'_'.str_pad($projectStrainNumber, 4, '0', STR_PAD_LEFT);

        // Set autoName
        $this->setAutoName($autoName);
        $project->setLastStrainNumber($projectStrainNumber);

    }
}
