<?php

/*
 * Copyright 2016-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Species.
 *
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="App\Repository\SpeciesRepository")
 * @UniqueEntity({"name", "genus"}, message="This name is already used by another species.")
 */
class Species
{
    const NUM_ITEMS = 10;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="genus"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="name"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="-"),
     *          @Gedmo\SlugHandlerOption(name="urilize", value=true)
     *      })
     * }, fields={"name"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Genus", inversedBy="species", cascade={"persist"})
     * @Assert\Valid
     */
    private $genus;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("#^sp.|(?:[a-z]+ var. )?[a-z]+$#m", message="The species must be in small letters, sp. or var. (eg: cerevisiae, sp., lactis var. lactis)")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="taxId", type="integer", nullable=true)
     * @Assert\Type("integer")
     */
    private $taxId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Species", inversedBy="synonyms")
     */
    private $mainSpecies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Species", mappedBy="mainSpecies", cascade={"persist"}, orphanRemoval=true)
     * @Assert\Valid
     */
    private $synonyms;

    /**
     * @var Strain|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Strain", mappedBy="species")
     */
    private $strains;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="strains")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    private $updatedBy;

    public function __construct()
    {
        $this->synonyms = new ArrayCollection();
        $this->strains = new ArrayCollection();
    }

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get slug.
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set genus.
     *
     * @param string $genus
     */
    public function setGenus(Genus $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    /**
     * Get genus.
     */
    public function getGenus(): string
    {
        return $this->genus;
    }

    /**
     * Set species.
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get species.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set TaxId.
     *
     * @param $taxId
     *
     * @return $this
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;

        return $this;
    }

    /**
     * Get TaxId.
     */
    public function getTaxId(): string
    {
        return $this->taxId;
    }

    /**
     * Set main.
     *
     * @param Species $species
     *
     * @return $this
     */
    public function setMainSpecies(self $species)
    {
        $this->mainSpecies = $species;

        return $this;
    }

    /**
     * Get main.
     */
    public function getMainSpecies(): self
    {
        return $this->mainSpecies;
    }

    /**
     * Add synonym.
     *
     * @param Species $species
     *
     * @return $this
     */
    public function addSynonym(self $species)
    {
        // A synonym can't have the same name than the main Species
        if ($this->getScientificName() === $species->getScientificName()) {
            return $this;
        }

        foreach ($this->synonyms as $synonym) {
            // We don't want many identical synonyms
            if ($synonym->getScientificName() === $species->getScientificName()) {
                return $this;
            }
        }

        // If the synonyme is unique and not the same than main, add it
        $species->setMainSpecies($this);
        $this->synonyms->add($species);

        return $this;
    }

    /**
     * Remove synoym.
     *
     * @param Species $species
     *
     * @return $this
     */
    public function removeSynonym(self $species)
    {
        if ($this->synonyms->contains($species)) {
            $this->synonyms->removeElement($species);
        }

        return $this;
    }

    /**
     * Get synonyms.
     */
    public function getSynonyms(): ArrayCollection
    {
        return $this->synonyms;
    }

    /**
     * Get gmo strains.
     */
    public function getStrains()
    {
        return $this->strains;
    }

    /**
     * Get scientificName.
     */
    public function getScientificName(): string
    {
        return $this->genus->getName().' '.$this->name;
    }

    public function isMainSpecies()
    {
        return (null !== $this->mainSpecies) ? false : true;
    }

    /**
     * Get created.
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * Get updated.
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * Get created by.
     */
    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    /**
     * Is author ?
     */
    public function isAuthor(User $user): bool
    {
        return $user === $this->createdBy;
    }

    /**
     * Get updated by.
     */
    public function getUpdatedBy(): User
    {
        return $this->updatedBy;
    }
}
