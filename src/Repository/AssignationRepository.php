<?php

namespace App\Repository;

use App\Entity\Assignation;
use App\Entity\Carte;
use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AssignationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Assignation::class);
    }

    /**
     * @param Carte $carte
     * @param Equipe $equipe
     * @return mixed
     */
    public function findByCarteEtEquipeEnCours(Carte $carte, Equipe $equipe){
        return $this->createQueryBuilder('a')
            ->where('a.carte = :carte')
            ->setParameter('carte', $carte)
            ->andWhere('a.equipe = :equipe')
            ->setParameter('equipe', $equipe)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
