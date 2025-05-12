<?php declare(strict_types=1);

namespace EventTask\Core\Content\Event;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class EventEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var \DateTimeInterface
     */
    protected $eventDate;

    /**
     * @var string|null
     */
    protected $organizedById;

    /**
     * @var CustomerEntity|null
     */
    protected $organizedBy;

    /**
     * @var EntityCollection|null
     */
    protected $eventCategories;

    /**
     * @var EntityCollection
     */
    protected $translations;

    /**
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    /**
     * @var array|null
     */
    protected $translated;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getEventDate(): \DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): void
    {
        $this->eventDate = $eventDate;
    }

    public function getOrganizedById(): ?string
    {
        return $this->organizedById;
    }

    public function setOrganizedById(?string $organizedById): void
    {
        $this->organizedById = $organizedById;
    }

    public function getOrganizedBy(): ?CustomerEntity
    {
        return $this->organizedBy;
    }

    public function setOrganizedBy(?CustomerEntity $organizedBy): void
    {
        $this->organizedBy = $organizedBy;
    }

    public function getEventCategories(): ?EntityCollection
    {
        return $this->eventCategories;
    }

    public function setEventCategories(?EntityCollection $eventCategories): void
    {
        $this->eventCategories = $eventCategories;
    }

    public function getTranslations(): EntityCollection
    {
        return $this->translations;
    }

    public function setTranslations(EntityCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getTranslated(): array
    {
        return $this->translated;
    }

    public function setTranslated(?array $translated): void
    {
        $this->translated = $translated;
    }
}