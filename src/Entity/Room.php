<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    private const PRICE_PER_HOUR = 2;
    private const HOUR_IN_SECONDS = 3600;
    private const MAX_BOOK_TIME = 4 * self::HOUR_IN_SECONDS;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onlyForPremiumMembers;

    /**
     * @ORM\OneToMany(targetEntity=Bookings::class, mappedBy="bookedRoom")
     */
    private $bookings;

    #[Pure] public function __construct(bool $onlyForPremiumMembers)
    {
        $this->onlyForPremiumMembers = $onlyForPremiumMembers;
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOnlyForPremiumMembers(): bool
    {
        return $this->onlyForPremiumMembers;
    }

    public function setOnlyForPremiumMembers(bool $onlyForPremiumMembers): self
    {
        $this->onlyForPremiumMembers = $onlyForPremiumMembers;

        return $this;
    }

    /**
     * @return Collection|Bookings[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Bookings $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setBookedRoom($this);
        }

        return $this;
    }

    public function removeBooking(Bookings $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getBookedRoom() === $this) {
                $booking->setBookedRoom(null);
            }
        }

        return $this;
    }

    public function canBook(User $user, Bookings $booking): bool
    {
        $timeDiff = $booking->getEndDate()->getTimestamp() - $booking->getStartDate()->getTimestamp();
        $bill = (ceil(abs($timeDiff) / self::HOUR_IN_SECONDS)) * self::PRICE_PER_HOUR;
        $isValid = true;

        if ($this->getOnlyForPremiumMembers() && !$user->getPremiumMember()) {
            $isValid = false;
        }

        if ($timeDiff <= 0 || abs($timeDiff) > self::MAX_BOOK_TIME) {
            $isValid = false;
        }

        if ($bill > $user->getCredit()) {
            $isValid = false;
        }

        $bookings = $this->getBookings();
        foreach ($bookings as $booked)
        {
            if($booking->getStartDate()->getTimestamp() < $booked->getEndDate()->getTimestamp() && $booking->getEndDate()->getTimestamp() > $booked->getStartDate()->getTimestamp())
            {
                $isValid = false;
            }
        }

        return $isValid;
    }
}
