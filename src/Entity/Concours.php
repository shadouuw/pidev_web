<?php

namespace App\Entity;
use  Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concours
 *
 * @ORM\Table(name="concours")
 * @ORM\Entity
 */
class Concours
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_concours", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idConcours;

    /**
     * @var string
     *  @Assert\Length(min=10,minMessage="name length must be greater than 10 ")
     * @Assert\NotNull(message="name is empty ")
     * @ORM\Column(name="nom_concours", type="string", length=60, nullable=false)
     */
    private $nomConcours;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
    private $prix;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
    private $dateFin;

    /**
     * @var int
     *
     * @ORM\Column(name="is_done", type="integer", nullable=false)
     */
    private $isDone = '0';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img;

    public function getIdConcours(): ?int
    {
        return $this->idConcours;
    }

    public function getNomConcours(): ?string
    {
        return $this->nomConcours;
    }

    public function setNomConcours(string $nomConcours): self
    {
        $this->nomConcours = $nomConcours;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getIsDone(): ?int
    {
        return $this->isDone;
    }

    public function setIsDone(int $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }


}
