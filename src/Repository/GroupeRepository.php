<?php

namespace App\Repository;

use App\Entity\Groupe;
use App\Entity\Module;
use App\Entity\ModuleParent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }
    public function afficheGroupe()
    {
        return $this->createQueryBuilder('g')
            ->select('g.titre','m.titre','p.titre')
            ->innerJoin(Module::class,'m','WITH','g.module=m.id')
            ->innerJoin(ModuleParent::class,'p','WITH','m.parent=p.id')
            ->getQuery()
            ->getResult()
            ;
    }

    public function afficheModule()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = "
            SELECT g.titre as groupe,m.titre as module,p.titre as parent,m.ordre,m.icon,g.module_id 
            FROM groupe g
            INNER JOIN module as m on g.module_id=m.id
            INNER JOIN module_parent as p on m.parent_id=p.id
            group by m.id
            order by m.ordre
           
            ";
        $stmt = $conn->executeQuery($sql);
        return $stmt->fetchAll();

    }

    public function afficheGroupes()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = "
            SELECT g.titre as groupe,m.titre as module,g.ordre,g.lien,g.icon
            FROM groupe g
            INNER JOIN module as m on g.module_id=m.id
             order by g.ordre
           
            ";
        $stmt = $conn->executeQuery($sql);
        return $stmt->fetchAll();

    }
    public function affiche()
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = "
            SELECT p.titre as parent,p.ordre
            FROM module_parent p
           order by p.ordre
           
            ";
        $stmt = $conn->executeQuery($sql);
        return $stmt->fetchAll();

    }


    // /**
    //  * @return Groupe[] Returns an array of Groupe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Groupe
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
