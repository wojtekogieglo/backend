<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product implements ProductInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     */
    private string $info;

    /**
     * @ORM\Column(name="public_date", type="date")
     */
    private \DateTimeInterface $publicDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", mappedBy="products")
     */
    private Collection $people;

    public function __construct()
    {
        $this->people = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    public function getPublicDate(): ?\DateTimeInterface
    {
        return $this->publicDate;
    }

    public function setPublicDate(\DateTimeInterface $publicDate): void
    {
        $this->publicDate = $publicDate;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->addProduct($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->removeElement($person)) {
            $person->removeProduct($this);
        }

        return $this;
    }
}
