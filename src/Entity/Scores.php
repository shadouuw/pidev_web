<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scores
 *
 * @ORM\Table(name="scores")
 * @ORM\Entity
 */
class Scores
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
     * @ORM\Column(name="id_user", type="string", length=150, nullable=false)
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_jeux", type="integer", nullable=false)
     */
    private $idJeux;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?string
    {
        return $this->idUser;
    }

    public function setIdUser(string $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdJeux(): ?int
    {
        return $this->idJeux;
    }

    public function setIdJeux(int $idJeux): self
    {
        $this->idJeux = $idJeux;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }


}
