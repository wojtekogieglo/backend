<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(
     *     min = 1,
     *     max = 255,
     *     minMessage = "Nazwa musi mieć przynajmniej 1 znak",
     *     maxMessage = "Nazwa może mieć maksymalnie 255 znaków"
     * )
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

    public function addPerson(PersonInterface $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->addProduct($this);
        }

        return $this;
    }

    public function removePerson(PersonInterface $person): self
    {
        if ($this->people->removeElement($person)) {
            $person->removeProduct($this);
        }

        return $this;
    }

    public function hasPerson(PersonInterface $person): bool
    {
        return $this->people->contains($person);
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
