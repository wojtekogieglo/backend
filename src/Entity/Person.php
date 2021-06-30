<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepositoryInterface")
 */
class Person implements PersonInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $login;

    /**
     * @ORM\Column(name="f_name", type="string", length=100)
     */
    private string $firstName;

    /**
     * @ORM\Column(name="l_name", type="string", length=100)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="smallint")
     */
    private int $state = self::STATE_ACTIVE;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="people")
     */
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): void
    {
        Assert::oneOf(
            $state,
            [self::STATE_ACTIVE, self::STATE_BANNED, self::STATE_DELETED],
            sprintf('Wrong state "%s" given.', $state)
        );

        $this->state = $state;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $products): void
    {
        if (!$this->products->contains($products)) {
            $this->products[] = $products;
        }
    }

    public function removeProduct(Product $products): void
    {
        $this->products->removeElement($products);
    }
}
