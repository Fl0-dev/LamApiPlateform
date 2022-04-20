<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $cv;

    #[ORM\Column(type: 'text', nullable: true)]
    private $message;

    #[ORM\Column(type: 'datetime')]
    private $submitDate;

    #[ORM\ManyToOne(targetEntity: Applicant::class, inversedBy: 'applications')]
    private $applicant;

    #[ORM\ManyToOne(targetEntity: Offer::class, inversedBy: 'applications')]
    private $offer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSubmitDate(): ?\DateTimeInterface
    {
        return $this->submitDate;
    }

    public function setSubmitDate(\DateTimeInterface $submitDate): self
    {
        $this->submitDate = $submitDate;

        return $this;
    }

    public function getApplicant(): ?Applicant
    {
        return $this->applicant;
    }

    public function setApplicant(?Applicant $applicant): self
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }
}
