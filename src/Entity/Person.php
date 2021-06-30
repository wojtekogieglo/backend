<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert as WebmozartAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
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
     * @Assert\Length(
     *     min = 1,
     *     max = 10,
     *     minMessage = "Login musi mieć przynajmniej 1 znak",
     *     maxMessage = "Login może mieć maksymalnie 10 znaków"
     * )
     */
    private string $login;

    /**
     * @ORM\Column(name="f_name", type="string", length=100)
     * @Assert\Length(
     *     min = 1,
     *     max = 100,
     *     minMessage = "Imię musi mieć przynajmniej 1 znak",
     *     maxMessage = "Imię może mieć maksymalnie 100 znaków"
     * )
     */
    private string $firstName;

    /**
     * @ORM\Column(name="l_name", type="string", length=100)
     * @Assert\Length(
     *     min = 1,
     *     max = 100,
     *     minMessage = "Nazwisko musi mieć przynajmniej 1 znak",
     *     maxMessage = "Nazwisko może mieć maksymalnie 100 znaków"
     * )
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
        WebmozartAssert::oneOf(
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

    public static function getStateSelectionMethodLabels(): array
    {
        return [
            self::STATE_ACTIVE => 'Aktywny',
            self::STATE_BANNED => 'Zbanowany',
            self::STATE_DELETED => 'Usunięty',
        ];
    }
}
