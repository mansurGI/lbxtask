<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    /**
     * @param int $amount The maximum amount of Employees
     * @return Employee[] Returns an array of newest Employees
     */
    public function findLatest(int $amount = 100): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->setMaxResults($amount)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Insert with ignore statement
     *
     * @param array $params Employee as array
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function insertIgnore(array $params): void
    {
        $sql = $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->insert('employee')
            ->values(array_fill_keys(array_keys($params), '?'))
            ->setParameters($params)
            ->getSQL();

        $this->getEntityManager()->getConnection()->executeStatement(
            str_replace('INSERT', 'INSERT IGNORE', $sql),
            array_values($params),
        );
    }

//    /**
//     * @return Employee[] Returns an array of Employee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Employee
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
