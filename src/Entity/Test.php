<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
@ORM\Entity(repositoryClass="App\Repository\TestRepository")
 */
/**
 * Test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity
 */
class Test
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_test", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTest;

    /**
     * @var string|null
     *
     * @ORM\Column(name="question_1", type="string", length=60, nullable=true)
     */
    private $question1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="question_2", type="string", length=60, nullable=true)
     */
    private $question2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="question_3", type="string", length=60, nullable=true)
     */
    private $question3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="question_4", type="string", length=60, nullable=true)
     */
    private $question4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="question_5", type="string", length=60, nullable=true)
     */
    private $question5;

    /**
     * @var int|null
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=true)
     */
    private $idUtilisateur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=60, nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reponse_1", type="string", length=60, nullable=true)
     */
    private $reponse1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reponse_2", type="string", length=60, nullable=true)
     */
    private $reponse2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reponse_3", type="string", length=60, nullable=true)
     */
    private $reponse3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reponse_4", type="string", length=60, nullable=true)
     */
    private $reponse4;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reponse_5", type="string", length=60, nullable=true)
     */
    private $reponse5;

    /**
     * @var int|null
     *
     * @ORM\Column(name="temps", type="integer", nullable=true)
     */
    private $temps;

    /**
     * @var int
     *
     * @ORM\Column(name="is_compt", type="integer", nullable=false)
     */
    private $isCompt = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_tournoi", type="integer", nullable=true)
     */
    private $idTournoi;

    public function getIdTest(): ?int
    {
        return $this->idTest;
    }

    public function getQuestion1(): ?string
    {
        return $this->question1;
    }

    public function setQuestion1(?string $question1): self
    {
        $this->question1 = $question1;

        return $this;
    }

    public function getQuestion2(): ?string
    {
        return $this->question2;
    }

    public function setQuestion2(?string $question2): self
    {
        $this->question2 = $question2;

        return $this;
    }

    public function getQuestion3(): ?string
    {
        return $this->question3;
    }

    public function setQuestion3(?string $question3): self
    {
        $this->question3 = $question3;

        return $this;
    }

    public function getQuestion4(): ?string
    {
        return $this->question4;
    }

    public function setQuestion4(?string $question4): self
    {
        $this->question4 = $question4;

        return $this;
    }

    public function getQuestion5(): ?string
    {
        return $this->question5;
    }

    public function setQuestion5(?string $question5): self
    {
        $this->question5 = $question5;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReponse1(): ?string
    {
        return $this->reponse1;
    }

    public function setReponse1(?string $reponse1): self
    {
        $this->reponse1 = $reponse1;

        return $this;
    }

    public function getReponse2(): ?string
    {
        return $this->reponse2;
    }

    public function setReponse2(?string $reponse2): self
    {
        $this->reponse2 = $reponse2;

        return $this;
    }

    public function getReponse3(): ?string
    {
        return $this->reponse3;
    }

    public function setReponse3(?string $reponse3): self
    {
        $this->reponse3 = $reponse3;

        return $this;
    }

    public function getReponse4(): ?string
    {
        return $this->reponse4;
    }

    public function setReponse4(?string $reponse4): self
    {
        $this->reponse4 = $reponse4;

        return $this;
    }

    public function getReponse5(): ?string
    {
        return $this->reponse5;
    }

    public function setReponse5(?string $reponse5): self
    {
        $this->reponse5 = $reponse5;

        return $this;
    }

    public function getTemps(): ?int
    {
        return $this->temps;
    }

    public function setTemps(?int $temps): self
    {
        $this->temps = $temps;

        return $this;
    }

    public function getIsCompt(): ?int
    {
        return $this->isCompt;
    }

    public function setIsCompt(int $isCompt): self
    {
        $this->isCompt = $isCompt;

        return $this;
    }

    public function getIdTournoi(): ?int
    {
        return $this->idTournoi;
    }

    public function setIdTournoi(?int $idTournoi): self
    {
        $this->idTournoi = $idTournoi;

        return $this;
    }


}
