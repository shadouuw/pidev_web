<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Jeux
 *
 * @ORM\Table(name="jeux")
 * @ORM\Entity
 * *@Vich\Uploadable
 */
class Jeux
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=40, nullable=false)
     * @Groups("post:read")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Title must be at least {{ limit }} characters long",
     *      maxMessage = "Title cannot be longer than {{ limit }} characters"
     * )
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     * @Groups("post:read")
     */
    private $description;

    /**
     * @var string|null
     *
     * @Groups("post:read")
     * @ORM\Column(name="topscore", type="string", length=40, nullable=true)
     * @Assert\Positive(message="TopScore should be positive.")
     * @Assert\Range(
     *      min = 0,
     *      max = 100000000,
     *      notInRangeMessage = "TopScore must be between {{ min }} and {{ max }} TND ",
     * )
     */
    private $topscore;



    /**
     * @var string
     *
     * @ORM\Column(name="diff", type="string", length=40, nullable=false)
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      notInRangeMessage = "Difficulty must be between {{ min }} and {{ max }}",
     * )
     * @Groups("post:read")
     */
    private $diff;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source", type="string", length=500, nullable=true)
     * @Assert\Expression("this.getsource() or this.getImageFile()", message="you must upload a photo.")
     * @Groups("post:read")
     */
    private $source;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="source")
     * @Groups("post:read")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity=Cours::class)
     * @ORM\JoinColumn(name="cours",referencedColumnName="id_cours",nullable=false)
     *  @Groups("post:read")
     */
    private $cours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTopscore(): ?string
    {
        return $this->topscore;
    }

    public function setTopscore(?string $topscore): self
    {
        $this->topscore = $topscore;

        return $this;
    }



    public function getDiff(): ?string
    {
        return $this->diff;
    }

    public function setDiff(string $diff): self
    {
        $this->diff = $diff;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function setImageFile($source = null)
    {
        $this->imageFile = $source;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($source) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }





    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }
}
