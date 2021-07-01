<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface PersonInterface
{
    public const STATE_ACTIVE = 1;

    public const STATE_BANNED = 2;

    public const STATE_DELETED = 3;

    public function getId(): ?int;

    public function getLogin(): ?string;

    public function setLogin(string $login): void;

    public function getFirstName(): ?string;

    public function setFirstName(string $firstName): void;

    public function getLastName(): ?string;

    public function setLastName(string $lastName): void;

    public function getState(): ?int;

    public function setState(int $state): void;

    public function getProducts(): Collection;

    public function addProduct(ProductInterface $product): void;

    public function removeProduct(ProductInterface $product): void;

    public function hasProduct(ProductInterface $product): bool;
}
