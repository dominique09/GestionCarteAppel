<?php

namespace App\Repository;

use App\Entity\Intervenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class IntervenantRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Intervenant::class);
    }

    public function findByInitiales($initiales){
        return $this->createQueryBuilder('i')
            ->where('i.initiales = :initiales')
            ->setParameter('initiales', $initiales)
            ->getQuery()
            ->getResult();
    }

    public function notAssigned(){
        return $this->createQueryBuilder('i')
            ->where('i.user is null')
            ->orderBy('i.firstname', 'ASC')
            ->orderBy('i.lastname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('i')
            ->where('i.something = :value')->setParameter('value', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
