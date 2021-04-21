<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jeux
 *
 * @ORM\Table(name="jeux")
 * @ORM\Entity
 */
class Jeux
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=40, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="topscore", type="string", length=40, nullable=true)
     */
    private $topscore;

    /**
     * @var int
     *
     * @ORM\Column(name="cours", type="integer", nullable=false)
     */
    private $cours;

    /**
     * @var string
     *
     * @ORM\Column(name="diff", type="string", length=40, nullable=false)
     */
    private $diff;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source", type="string", length=500, nullable=true)
     */
    private $source;

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

    public function getCours(): ?int
    {
        return $this->cours;
    }

    public function setCours(int $cours): self
    {
        $this->cours = $cours;

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


}
