<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Stages
 *
 * @ORM\Table(name="stages")
 * @ORM\Entity
 **@Vich\Uploadable
 */
class Stages
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @Groups("post:read")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *@Groups("post:read")
     * @ORM\Column(name="numero", type="integer", nullable=false)
     * @Assert\Type(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 15,
     *      notInRangeMessage = "Numero must be between {{ min }} and {{ max }}",
     * )
     */
    private $numero;

    /**
     * @var string
     *@Groups("post:read")
     * @ORM\Column(name="texte", type="string", length=100, nullable=false)
     */
    private $texte;

    /**
     * @var string
     *@Groups("post:read")
     * @ORM\Column(name="essai", type="string", length=100, nullable=false)
     */
    private $essai;

    /**
     * @var string
     *@Groups("post:read")
     * @ORM\Column(name="correction", type="string", length=100, nullable=false)
     */
    private $correction;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source", type="string", length=100, nullable=true)
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
     * @var int
     *@Groups("post:read")
     * @ORM\Column(name="temps", type="integer", nullable=false)
     * @Assert\Range(
     *      min = 5,
     *
     *     notInRangeMessage = "Temps must be supperior than {{ min }}",
     * )
     */
    private $temps;



    /**
     * @var int
     * @ORM\ManyToOne(targetEntity=Jeux::class)
     * @ORM\JoinColumn(name="jeu",referencedColumnName="id",nullable=false)
     *  @Groups("post:read")
     *
     */
    private $jeu;



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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getEssai(): ?string
    {
        return $this->essai;
    }

    public function setEssai(string $essai): self
    {
        $this->essai = $essai;

        return $this;
    }

    public function getCorrection(): ?string
    {
        return $this->correction;
    }

    public function setCorrection(string $correction): self
    {
        $this->correction = $correction;

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

    public function getTemps(): ?int
    {
        return $this->temps;
    }

    public function setTemps(int $temps): self
    {
        $this->temps = $temps;

        return $this;
    }

    public function getJeu(): ?Jeux
    {
        return $this->jeu;
    }

    public function setJeu(?Jeux $jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }


}
