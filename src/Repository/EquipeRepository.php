<?php

namespace App\Repository;

use App\Entity\Carte;
use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    public function findActive(){
        return $this->createQueryBuilder('e')
            ->where('e.createdAt is not null')
            ->andWhere('e.dissolvedAt is null')
            ->orderBy('e.identifiant', 'ASC')
            ->orderBy('e.tempsIndispo', 'ASC')
            ->orderBy('e.retourVersCo', 'ASC')
            ->orderBy('e.finDispoAppels', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByIdentifiant($identifiant){
        return $this->createQueryBuilder('e')
            ->where('e.createdAt is not null')
            ->andWhere('e.dissolvedAt is null')
            ->andWhere('e.identifiant = :ident')
            ->setParameter('ident', $identifiant)
            ->getQuery()
            ->getResult();
    }

    public function findAllDispo(){
        return $this->createQueryBuilder('e')
            ->where('e.createdAt is not null')
            ->andWhere('e.debutDispoAppels is not null')
            ->andWhere('e.dissolvedAt is null')
            ->andWhere('e.tempsIndispo is null')
            ->andWhere('e.retourVersCo is null')
            ->getQuery()
            ->getResult();
    }

    public function findLatestEdited(){
        return $this->createQueryBuilder('e')
            ->select('e.updatedAt')
            ->getMaxResults();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('e')
            ->where('e.something = :value')->setParameter('value', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
