<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment;

    /**
     * @var Hotel
     * @ORM\ManyToOne(targetEntity="Hotel")
     * @ORM\JoinColumn(name="hotel_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $hotel;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }
}
