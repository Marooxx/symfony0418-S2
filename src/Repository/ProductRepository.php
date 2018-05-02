<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Récupère le produit ainsi que sa catégorie avec l'id donné
     * @param int $id
     * @return Product|null
     * @throws \Exception
     */
    public function findOneWithCategory(int $id): ?Product
    {

        $query = $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->addSelect('c')
            ->where('p.id = :id')->setParameter(":id", $id)
            ->getQuery()
        ;

        try {
            return $query->getOneOrNullResult();
        } catch (\Exception $e) {
            throw new \Exception(
                'Probleme dans ProductRepository::findOneWithCategory'.
                $e->getMessage() .
                var_dump($e)
            );
        }
    }

}
