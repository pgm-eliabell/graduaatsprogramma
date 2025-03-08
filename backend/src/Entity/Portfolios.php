<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\PortfoliosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: PortfoliosRepository::class)]
#[ORM\Table(name: "portfolios")]
#[Broadcast]
class Portfolios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(
        inversedBy: 'portfolios',
        cascade: ['persist', 'remove'],
        targetEntity: User::class
    )]
    #[ORM\JoinColumn(name: "user_id_id", referencedColumnName: "id")]
    private ?User $user = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $professionTitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?array $layoutConfig = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\Column]
    private ?int $views = null;

    /**
     * @var Collection<int, PortfolioComponents>
     */
    #[ORM\OneToMany(targetEntity: PortfolioComponents::class, mappedBy: 'portfolioId')]
    private Collection $portfolioComponents;

    public function __construct()
    {
        $this->portfolioComponents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getProfessionTitle(): ?string
    {
        return $this->professionTitle;
    }

    public function setProfessionTitle(?string $professionTitle): static
    {
        $this->professionTitle = $professionTitle;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getLayoutConfig(): ?array
    {
        return $this->layoutConfig;
    }

    public function setLayoutConfig(?array $layoutConfig): static
    {
        $this->layoutConfig = $layoutConfig;
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

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;
        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): static
    {
        $this->views = $views;
        return $this;
    }

    /**
     * @return Collection<int, PortfolioComponents>
     */
    public function getPortfolioComponents(): Collection
    {
        return $this->portfolioComponents;
    }

    public function addPortfolioComponent(PortfolioComponents $portfolioComponent): static
    {
        if (!$this->portfolioComponents->contains($portfolioComponent)) {
            $this->portfolioComponents->add($portfolioComponent);
            $portfolioComponent->setPortfolioId($this);
        }
        return $this;
    }

    public function removePortfolioComponent(PortfolioComponents $portfolioComponent): static
    {
        if ($this->portfolioComponents->removeElement($portfolioComponent)) {
            if ($portfolioComponent->getPortfolioId() === $this) {
                $portfolioComponent->setPortfolioId(null);
            }
        }
        return $this;
    }
}