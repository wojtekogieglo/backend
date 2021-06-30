<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface ProductInterface
{
    public function getId(): ?int;

    public function getName(): ?string;

    public function setName(string $name): void;

    public function getInfo(): ?string;

    public function setInfo(string $info): void;

    public function getPublicDate(): ?\DateTimeInterface;

    public function setPublicDate(\DateTimeInterface $publicDate): void;

    public function getPeople(): Collection;

    public function addPerson(Person $person): self;

    public function removePerson(Person $person): self;
}
