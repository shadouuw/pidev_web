<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use  Symfony\Component\Validator\Constraints as Assert;


/**
 * Cours
 *
 * @ORM\Table(name="cours")
 * @ORM\Entity
 */
class Cours
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cours", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCours;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_cours", type="string", length=255, nullable=false)
     * @Assert\Length(min=5,max=15,minMessage="Course name length must be greater than 5 ")
     * @Assert\NotNull(message="Name is empty ")
     *
     */


    private $nomCours;

    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=255, nullable=false)
     *
     * @Assert\Choice(
     *         choices = {"Math", "French","Science"},
     *     message = "Choose a valid Domain. ex: Math ")
     * @Assert\NotNull(message="Domain is empty ")
     */

    private $domaine;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=255, nullable=false)
     */
    private $lien;

    /**
     * @var string
     *
     * @ORM\Column(name="lien2", type="string", length=255, nullable=false)
     */
    private $lien2;

    public function getIdCours(): ?int
    {
        return $this->idCours;
    }

    public function getNomCours(): ?string
    {
        return $this->nomCours;
    }

    public function setNomCours(string $nomCours): self
    {
        $this->nomCours = $nomCours;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getLien2(): ?string
    {
        return $this->lien2;
    }

    public function setLien2(string $lien2): self
    {
        $this->lien2 = $lien2;

        return $this;
    }


}
