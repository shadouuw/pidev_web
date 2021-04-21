<?php

namespace App\Repository;

use App\Entity\Cours;
use App\Entity\Stages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stages[]    findAll()
 * @method Stages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stages::class);
    }


public function Total_Stages()
{
    $qb = $this->createQueryBuilder('f');
    return $qb
        ->select('count(f.id)')
        ->getQuery()
        ->getSingleScalarResult();
}



    function triStages(){
        $qb = $this->createQueryBuilder('f');
        return $qb
            ->select('* order by jeu desc')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findjeu(){

        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT s,g.titre FROM App\Entity\Jeux g   JOIN  App\Entity\Stages s WITH  s.jeu=g.id order by g.titre ASC'
        );
        return $query->getResult();
    }

    public function findjeud(){

        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT s,g.titre FROM App\Entity\Jeux g   JOIN  App\Entity\Stages s WITH  s.jeu=g.id order by g.titre DESC'
        );
        return $query->getResult();


    }



    public function findjeut($titre)
    {

        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT s,g.titre FROM App\Entity\Jeux g   JOIN  App\Entity\Stages s WITH  s.jeu=g.id
            where g.titre like :t
            '
        );
        $query->setParameter('t',$titre.'%');
        return $query->getResult();
    }



    function firststage($id)
        {

       $qb=$this->createQueryBuilder('s')->select('s')
                ->where('s.jeu=:isSubscribe and s.numero=1')
                ->setParameter('isSubscribe', $id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
       return $qb;
        }


    function firststagenum($id)
    {

        $qb=$this->createQueryBuilder('s')->select('max(s.numero)')
            ->where('s.jeu=:isSubscribe')
            ->setParameter('isSubscribe', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();
        return $qb;
    }


    function nextstage($id,$fs)
    {

        $qb=$this->createQueryBuilder('s')->select('s')
            ->where('s.jeu=:isSubscribe and s.numero=:num')
            ->setParameter('isSubscribe', $id)
            ->setParameter('num',$fs)

            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $qb;
    }




    /* public function findAlll()
     {
         return $this->createQueryBuilder('j')
             ->where('j.user =:id')
             ->setParameter('id' , $id)
             ->getQuery()->getResult();
     }*/
/*
    function Total_Jeux(){
        $qb = $this->createQueryBuilder('f');
        return $qb
            ->select('count(f.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    function Total_Cours(){
        $qb = $this->createQueryBuilder('f');
        return $qb
            ->select('count(DISTINCT f.cours)')
            ->getQuery()
            ->getSingleScalarResult();
    }*/
/*

*/
    /*
    public function findCours(){

        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g,c.nomCours FROM App\Entity\Cours c   JOIN  App\Entity\Jeux g WITH  g.cours=c.idCours order by g.titre ASC'
        );
        return $query->getResult();
    }

    public function findCoursd(){

        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g,c.nomCours FROM App\Entity\Cours c   JOIN  App\Entity\Jeux g WITH  g.cours=c.idCours order by g.titre DESC'
        );
        return $query->getResult();
    }


    public function findCourst($titre){

        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g,c.nomCours FROM App\Entity\Cours c   JOIN  App\Entity\Jeux g WITH  g.cours=c.idCours
            where g.titre like :t
            '
        );
        $query->setParameter('t',$titre.'%');
        return $query->getResult();
    }




    // /**
    //  * @return Cours[] Returns an array of Cours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cours
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}

