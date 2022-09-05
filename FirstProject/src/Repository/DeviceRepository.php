<?php

namespace App\Repository;

use App\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Device>
 *
 * @method Device|null find($id, $lockMode = null, $lockVersion = null)
 * @method Device|null findOneBy(array $criteria, array $orderBy = null)
 * @method Device[]    findAll()
 * @method Device[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Device $entity, bool $flush = true): void
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
    public function remove(Device $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Device[] Returns an array of Device objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Device
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function Devparclient($id)
    {$q=$this->_em->createQuery('SELECT d.serialnumber,d.designiation FROM App\Entity\Device d JOIN d.Abonnements a where (d.Abonnements=a.id AND a.User=:i AND a.isVerified=1)')
        ->setParameter('i',$id);
        return $q->getResult();
    }
    public function Devparabo($id)
    {$q=$this->_em->createQuery('SELECT d.id,d.serialnumber,d.designiation,a.nom FROM App\Entity\Device d JOIN d.domaineApplication a where (d.Abonnements=:i and d.domaineApplication=a.id)')
        ->setParameter('i',$id);
        return $q->getResult();
    }
    public function maxdevice()
    {$q=$this->_em->createQuery('SELECT min(d.id) FROM App\Entity\Device d where d.Abonnements is NULL');
        return $q->getResult()[0][1];
    }
    public function nboredevicedisponible()
    {$q=$this->_em->createQuery('SELECT count(d.id) FROM App\Entity\Device d where d.Abonnements is NULL');
        return $q->getResult()[0][1];
    }
    //la fonction qui retourne les devices pour chaque domaine
    public function getDeviceParDomaine($id)
    {$q=$this->_em->createQuery('SELECT d FROM App\Entity\Device d JOIN d.domaineApplication a where (d.Abonnements is NULL AND a.id=:i) ')
        ->setParameter('i',$id);
        return $q->getResult();
    }
    //fonction qui retourne le nombre de device affecté par abonnement
    public function getNBRDeviceParAbonnement($id)
    {$q=$this->_em->createQuery('SELECT count(d.id) FROM App\Entity\Device d JOIN d.Abonnements a where (d.Abonnements=a.id AND a.id=:i) ')
        ->setParameter('i',$id);
        return $q->getResult()[0][1];
    }
    //fonction qui retourne le nombre dedata d'un device
    public function getNBRDataByDevice($id)
    {$q=$this->_em->createQuery('SELECT count(d.data) FROM App\Entity\Donnee d where (d.device=:i) ')
        ->setParameter('i',$id);
        return $q->getResult()[0][1];
    }
    //fonction qui retourne le nombre dedata d'un device avec la contrainte de la date
    public function getNBRDataByDeviceByDate($id,$datedebut,$datefin)
    {$q=$this->_em->createQuery('SELECT count(d.data) FROM App\Entity\Donnee d where (d.device=:i and d.timestamp >=:datedebut and d.timestamp <=:datefin ) ')
        ->setParameter('i',$id)
        ->setParameter(':datedebut', "$datedebut%")
        ->setParameter('datefin',"$datefin%");
        return $q->getResult()[0][1];
    }
    //fonction qui retourne le data d'un device
    public function getDataByDevice($id)
    {$q=$this->_em->createQuery('SELECT d.data,d.timestamp FROM App\Entity\Donnee d where (d.device=:i) order by d.timestamp DESC ')
        ->setParameter('i',$id);
        return $q->getResult();
    }
    //fonction qui retourne le data d'un device by date
    public function getDataByDevicebyDate($id,$datedebut,$datefin)
    {$q=$this->_em->createQuery('SELECT d.data,d.timestamp FROM App\Entity\Donnee d where (d.device=:i and d.timestamp >=:datedebut and d.timestamp <=:datefin ) order by d.timestamp  ')
        ->setParameter('i',$id)
        ->setParameter(':datedebut', "$datedebut%")
        ->setParameter('datefin',"$datefin%");
        return $q->getResult();
    }
    //fonction qui retourne id autorisation pour un device donnee
    public function getIdAutorisation($id)
    {$q=$this->_em->createQuery('SELECT a.id as auto FROM App\Entity\Autorisation  a where (a.device=:i)')
        ->setParameter('i',$id);
        return $q->getResult()[0];
    }
}
