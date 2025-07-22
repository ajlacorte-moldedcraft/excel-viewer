<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Province;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Province::class)]
    #[ORM\JoinColumn(name: 'province', referencedColumnName: 'id', nullable: true)]
    private ?Province $province = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isMunicipality = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;
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

    public function isMunicipality(): bool
    {
        return $this->isMunicipality;
    }

    public function setIsMunicipality(bool $isMunicipality): static
    {
        $this->isMunicipality = $isMunicipality;
        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): static
    {
        $this->province = $province;
        return $this;
    }
}
