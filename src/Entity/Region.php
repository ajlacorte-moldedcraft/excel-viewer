<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Province;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, nullable: true, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Province::class)]
    private Collection $provinces;

    public function __construct()
    {
        $this->provinces = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Province>
     */
    public function getProvinces(): Collection
    {
        return $this->provinces;
    }

    public function addProvince(Province $province): static
    {
        if (!$this->provinces->contains($province)) {
            $this->provinces[] = $province;
            $province->setRegion($this);
        }

        return $this;
    }

    public function removeProvince(Province $province): static
    {
        if ($this->provinces->removeElement($province)) {
            if ($province->getRegion() === $this) {
                $province->setRegion(null);
            }
        }

        return $this;
    }
}
