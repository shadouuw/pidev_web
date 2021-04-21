<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stages
 *
 * @ORM\Table(name="stages")
 * @ORM\Entity
 */
class Stages
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
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="string", length=100, nullable=false)
     */
    private $texte;

    /**
     * @var string
     *
     * @ORM\Column(name="essai", type="string", length=100, nullable=false)
     */
    private $essai;

    /**
     * @var string
     *
     * @ORM\Column(name="correction", type="string", length=100, nullable=false)
     */
    private $correction;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=100, nullable=false)
     */
    private $source;

    /**
     * @var int
     *
     * @ORM\Column(name="temps", type="integer", nullable=false)
     */
    private $temps;

    /**
     * @var int
     *
     * @ORM\Column(name="jeu", type="integer", nullable=false)
     */
    private $jeu;

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

    public function setSource(string $source): self
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

    public function getJeu(): ?int
    {
        return $this->jeu;
    }

    public function setJeu(int $jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }


}
