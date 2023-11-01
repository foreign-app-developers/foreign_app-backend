<?php

namespace App\Repository;

use App\Entity\CourseForUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseForUser>
 *
 * @method CourseForUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseForUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseForUser[]    findAll()
 * @method CourseForUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseForUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseForUser::class);
    }

    public function add(CourseForUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function save(CourseForUser $courseForUser, $flush = true)
    {
        $this->getEntityManager()->persist($courseForUser);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CourseForUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCoursesByTeacherAndStudent(int $created_by, int $user_id)
    {
        return $this->createQueryBuilder('cfu')
            ->innerJoin('cfu.course', 'c')
            ->where('c.teacher = :teacherId')
            ->andWhere('cfu.user_id = :studentId')
            ->setParameter('teacherId', $created_by)
            ->setParameter('studentId', $user_id)
            ->getQuery()
            ->getResult();
    }

    public function findCoursesForStudent(int $id)
    {
        return $this->createQueryBuilder('cfu')
            ->where('cfu.user_id = :user_id')
            ->setParameter('user_id', $id)
            ->getQuery()
            ->getResult();
    }



//    /**
//     * @return CourseForUser[] Returns an array of CourseForUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CourseForUser
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
