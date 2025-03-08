<?php

namespace App\Entity;

use App\Repository\PortfolioComponentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: PortfolioComponentsRepository::class)]
#[Broadcast]
class PortfolioComponents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'portfolioComponents')]
    private ?portfolios $portfolioId = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $componentType = null;

    #[ORM\Column(nullable: true)]
    private ?array $content = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPortfolioId(): ?portfolios
    {
        return $this->portfolioId;
    }

    public function setPortfolioId(?portfolios $portfolioId): static
    {
        $this->portfolioId = $portfolioId;

        return $this;
    }

    public function getComponentType(): ?string
    {
        return $this->componentType;
    }

    public function setComponentType(?string $componentType): static
    {
        $this->componentType = $componentType;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
