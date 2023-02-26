<?php

namespace App\Repository;

use App\Entity\Music;
use App\Entity\MusicGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Music>
 *
 * @method Music|null find($id, $lockMode = null, $lockVersion = null)
 * @method Music|null findOneBy(array $criteria, array $orderBy = null)
 * @method Music[]    findAll()
 * @method Music[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Music::class);
    }

    public function save(Music $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Music $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    
    public function findUnassignedMusic()
    {
        return $this->createQueryBuilder('m')
            ->where('m.album IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findTracksByGroup(MusicGroup $group)
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.album', 'a')
            ->andWhere('a.id IS NULL')
            ->andWhere('t.owner_music_group = :group')
            ->setParameter('group', $group);
    }

    public function findTracksByGroupForEdit(MusicGroup $group)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.owner_music_group = :group')
            ->setParameter('group', $group);
    }

//    /**
//     * @return Music[] Returns an array of Music objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Music
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
