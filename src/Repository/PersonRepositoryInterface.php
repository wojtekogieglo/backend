<?php

namespace App\Repository;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Persistence\ObjectRepository;

interface PersonRepositoryInterface extends ObjectRepository, Selectable
{

}