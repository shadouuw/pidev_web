<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use  Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotNull(message="Class name is empty ")
     */
    private $nom_class;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min = 4 , max = 15 , minMessage="age need to be greater than 4 ",maxMessage="Age need be lower than 15")
     *  @Assert\NotNull(message="age is empty ")
     */
    private $age;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_utilisateur;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClass(): ?string
    {
        return $this->nom_class;
    }

    public function setNomClass(string $nom_class): self
    {
        $this->nom_class = $nom_class;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getidUtilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    public function setidUtilisateur(int $idUtilisateur): self
    {
        $this->id_utilisateur = $idUtilisateur;

        return $this;
    }



}
