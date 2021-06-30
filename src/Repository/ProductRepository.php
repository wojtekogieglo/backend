<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByFilters(array $data): array
    {
        $queryBuilder = $this->createQueryBuilder('o');

        foreach ($data as $field => $value) {
            if (!$this->getClassMetadata()->hasField($field) || is_null($value)) {
                continue;
            }

            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('o.' . $field, ':_' . $field))
                ->setParameter('_' . $field, $value);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
