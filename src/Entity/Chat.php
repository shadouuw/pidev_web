<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chat
 *
 * @ORM\Table(name="chat", indexes={@ORM\Index(name="user_emut", columns={"user_emut"}), @ORM\Index(name="user_receiv", columns={"user_receiv"})})
 * @ORM\Entity
 */
class Chat
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
     * @ORM\Column(name="message", type="string", length=200, nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnvoi", type="datetime", nullable=false)
     */
    private $dateenvoi;

    /**
     *
     * @ORM\Column(name="vu", type="boolean", nullable=false)
     */
    private $vu;



    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_emut", referencedColumnName="id")
     * })
     */
    private $userEmut;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_receiv", referencedColumnName="id")
     * })
     */
    private $userReceiv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateenvoi(): ?\DateTimeInterface
    {
        return $this->dateenvoi;
    }

    public function setDateenvoi(\DateTimeInterface $dateenvoi): self
    {
        $this->dateenvoi = $dateenvoi;

        return $this;
    }

    public function getUserEmut()
    {
        return $this->userEmut;
    }

    public function setUserEmut( $userEmut): self
    {
        $this->userEmut = $userEmut;

        return $this;
    }

    public function getUserReceiv()
    {
        return $this->userReceiv;
    }

    public function setUserReceiv( $userReceiv): self
    {
        $this->userReceiv = $userReceiv;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * @param mixed $vu
     */
    public function setVu($vu): void
    {
        $this->vu = $vu;
    }


}
