<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="`product`")
 */
class Product
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
     * @ORM\Column(type="integer")
     */
    private ?int $cpuUnit = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $ramMb = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $diskSizeGb = null;

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
     * @return int|null
     */
    public function getCpuUnit(): ?int
    {
        return $this->cpuUnit;
    }

    /**
     * @param int|null $cpuUnit
     */
    public function setCpuUnit(?int $cpuUnit): void
    {
        $this->cpuUnit = $cpuUnit;
    }

    /**
     * @return int|null
     */
    public function getRamMb(): ?int
    {
        return $this->ramMb;
    }

    /**
     * @param int|null $ramMb
     */
    public function setRamMb(?int $ramMb): void
    {
        $this->ramMb = $ramMb;
    }

    /**
     * @return int|null
     */
    public function getDiskSizeGb(): ?int
    {
        return $this->diskSizeGb;
    }

    /**
     * @param int|null $diskSizeGb
     */
    public function setDiskSizeGb(?int $diskSizeGb): void
    {
        $this->diskSizeGb = $diskSizeGb;
    }
}
