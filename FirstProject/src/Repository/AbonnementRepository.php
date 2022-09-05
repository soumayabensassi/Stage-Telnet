<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 *
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Abonnement $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Abonnement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Abonnement[] Returns an array of Abonnement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Abonnement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function abonnparclient($id)
    {$q=$this->_em->createQuery('SELECT d.id,d.nom ,d.enatentte FROM App\Entity\Abonnement d JOIN d.User u where (u.id=:i AND d.etat=0 AND d.isVerified=0 )')
        ->setParameter('i',$id);
        return $q->getResult();
    }
    public function abonnementparclient($id)
    {$q=$this->_em->createQuery('SELECT d.id,d.nom,d.chef,d.etat ,d.enatentte,d.dateexp,o.nom,o.nbrAcces,u.email FROM App\Entity\Abonnement d JOIN d.offre o  JOIN d.User u where (u.id=:i  AND d.isVerified=1 )')
        ->setParameter('i',$id);
        return $q->getResult();
    }
    public function idabonnementparclient($id)
    {$q=$this->_em->createQuery('SELECT d.id FROM App\Entity\Abonnement d JOIN d.User u where (u.id=:i  AND d.isVerified=1 )')
        ->setParameter('i',$id);
        return $q->getResult();
    }
    public function nbrabonnementparclient($id)
    {$q=$this->_em->createQuery('SELECT count(d.id) FROM App\Entity\Abonnement d JOIN d.User u where (u.id=:i  AND d.isVerified=1 )')
        ->setParameter('i',$id);
        return $q->getResult();
    }
    public function notifclient($id)
    {$q=$this->_em->createQuery('SELECT count(d.id) FROM App\Entity\Abonnement d JOIN d.User u where (u.id=:i  AND d.enatentte=1 )')
        ->setParameter('i',$id);
        return $q->getResult()[0][1];
    }
    public function nombreuserbyabonnement($idA)
    {$q=$this->_em->createQuery('SELECT count(u.id) FROM App\Entity\Abonnement d JOIN d.User u where (d.id=:ii AND d.isVerified=1 )')
        ->setParameter('ii',$idA);
        return $q->getResult()[0][1];
    }
    public function getabonnementbyuserandbydomaine($id,$n)
    {$q=$this->_em->createQuery('SELECT a FROM App\Entity\Abonnement a JOIN a.User c JOIN a.offre o JOIN o.domaineApplication d where (c.id=:i AND d.nom=:n AND a.offre=o.id AND d.id=o.domaineApplication and a.isVerified=1)')
        ->setParameter('i',$id)
        ->setParameter('n',$n);
        return $q->getResult();
    }
    //fonction qui permet de retourner les abonement personnalisé par domaine
    public function getabonnementPERSONALISEbyuserandbydomaine($id,$n)
    {$q=$this->_em->createQuery('SELECT a FROM App\Entity\Abonnement a JOIN a.User c JOIN a.DomaineApplication d where (c.id=:i AND d.nom=:n AND a.DomaineApplication=d.id and a.isVerified=1)')
        ->setParameter('i',$id)
        ->setParameter('n',$n);
        return $q->getResult();
    }
}
