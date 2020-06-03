<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * Находит автора по полному имени
     * @param string $fullName
     * @return Author|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByFullName(string $fullName): ?Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.fullName = :fullName')
            ->setParameter('fullName', $fullName)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Создаёт автора по полному имени
     * @param string $fullName
     * @return Author
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createAuthor(string $fullName): Author
    {
        $author = new Author();
        $author->setFullName($fullName);
        $this->getEntityManager()->persist($author);
        $this->getEntityManager()->flush();

        return $author;
    }

    /**
     * Возвращает авторов без произведений
     * @return iterable|Author[]
     */
    public function findAuthorsWithoutBooks(): iterable
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.books', 'b')
            ->groupBy('a')
            ->having('COUNT(b.id) = 0')
            ->getQuery()
            ->getResult();
    }

    /**
     * Возвращает авторов с произведениями и количество для каждого.
     * @return iterable
     */
    public function findAuthorsAndBooksCount(): iterable
    {
        return $this->createQueryBuilder('a')
            ->select(['a', 'COUNT(b.id)'])
            ->innerJoin('a.books', 'b')
            ->groupBy('a')
            ->getQuery()
            ->getResult();
    }
}
