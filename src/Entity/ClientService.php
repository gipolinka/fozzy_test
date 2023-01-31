<?php

namespace App\Entity;

use App\Repository\ClientServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="`client_service`")
 */
class ClientService
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     */
    private ?Uuid $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $name = null;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     */
    private Product $product;

    /**
     * @ORM\Column(name="created", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $created;

    /**
     * @ORM\Column(name="modified", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $modified;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     */
    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getCreated(): mixed
    {
        return $this->created;
    }

    /**
     * @param $created
     * @return void
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getModified(): mixed
    {
        return $this->modified;
    }

    /**
     * @param $modified
     * @return void
     */
    public function setModified($modified): void
    {
        $this->modified = $modified;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @ORM\PreFlush
     */
    public function updatedTimestamps(): void
    {
        $this->setModified(new \DateTime('now'));
        if ($this->getCreated() === null) {
            $this->setCreated(new \DateTime('now'));
        }
    }
}
