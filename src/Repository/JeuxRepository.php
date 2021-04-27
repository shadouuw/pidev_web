<?php

namespace App\Repository;

use App\Entity\Cours;
use App\Entity\Jeux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jeux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jeux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jeux[]    findAll()
 * @method Jeux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeuxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jeux::class);
    }

   /* public function findAlll()
    {
        return $this->createQueryBuilder('j')
            ->where('j.user =:id')
            ->setParameter('id' , $id)
            ->getQuery()->getResult();
    }*/

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
    }

    function triJeux(){
        $qb = $this->createQueryBuilder('f');
        return $qb
            ->select('* order by title desc')
            ->getQuery()
            ->getSingleScalarResult();
    }

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
            where (g.titre like :t and exists(select s.numero from App\Entity\Stages s where (g.id=s.jeu and s.numero=1)))
            '
        );
        $query->setParameter('t',$titre.'%');
        return $query->getResult();
    }

    public function availablegames(){

        $qb=$this->createQueryBuilder('g')->select('g')
            ->where('exists(select s.numero from App\Entity\Stages s where (g.id=s.jeu and s.numero=1))')
            ->getQuery()
            ->getResult();
        return $qb;
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

