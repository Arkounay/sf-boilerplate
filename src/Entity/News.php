<?php

namespace App\Entity;

use App\Form\Admin\TinymceType;
use App\Repository\NewsRepository;
use Arkounay\Bundle\QuickAdminGeneratorBundle\Attribute as QAG;
use Arkounay\Bundle\UxMediaBundle\Validator as MediaAssert;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[MediaAssert\Image]
    private ?string $image;

    #[ORM\Column(type: 'date')]
    #[Assert\NotNull]
    #[QAG\Sort]
    private ?DateTime $startDate;

    #[ORM\Column(type: 'date', nullable: true)]
    #[QAG\Field(twigName: 'news_datetime_end')]
    private ?DateTime $endDate;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[QAG\HideInList]
    #[QAG\Field(formType: TinymceType::class)]
    private ?string $content;

    #[ORM\Column(type: 'string', unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    #[QAG\Ignore]
    private string $slug;

    public function __construct()
    {
        $this->startDate = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): static
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        if ($this->endDate !== null && $this->endDate < $this->startDate) {
            $context->buildViolation('La date de fin doit être ultérieure à la date de publication')->atPath('endDate')->addViolation();
        }
    }



}
