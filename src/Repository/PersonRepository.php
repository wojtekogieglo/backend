<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\PersonInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository implements PersonRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findByFilters(array $data): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        foreach ($data as $field => $value) {
            if (!$this->getClassMetadata()->hasField($field) || is_null($value)) {
                continue;
            }

            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('p.' . $field, ':_' . $field))
                ->setParameter('_' . $field, $value);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
