<?php

namespace App\Entity;

use App\Repository\BookingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingsRepository::class)
 */
class Bookings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bookedBy;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bookedRoom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * Bookings constructor.
     * @param $bookedBy
     * @param $bookedRoom
     * @param $startDate
     * @param $endDate
     */
    public function __construct($bookedBy, $bookedRoom, $startDate, $endDate)
    {
        $this->bookedBy = $bookedBy;
        $this->bookedRoom = $bookedRoom;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookedBy(): ?User
    {
        return $this->bookedBy;
    }

    public function setBookedBy(?User $bookedBy): self
    {
        $this->bookedBy = $bookedBy;

        return $this;
    }

    public function getBookedRoom(): ?Room
    {
        return $this->bookedRoom;
    }

    public function setBookedRoom(?Room $bookedRoom): self
    {
        $this->bookedRoom = $bookedRoom;

        return $this;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

}
