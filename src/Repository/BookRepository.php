<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Находит книгу по названию, языку и автору
     * @param string $title
     * @param string $lang
     * @param \App\Entity\Author $author
     * @return Book|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByTitleLangAuthor(string $title, string $lang, \App\Entity\Author $author): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title = :title')
            ->setParameter('title', $title)
            ->andWhere('b.lang = :lang')
            ->setParameter('lang', $lang)
            ->andWhere('b.author = :author')
            ->setParameter('author', $author)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Создаёт книгу по названию, языку и автору
     * @param string $title
     * @param string $lang
     * @param \App\Entity\Author $author
     * @return Book
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createBook(string $title, string $lang, \App\Entity\Author $author): Book
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setLang($lang);
        $book->setAuthor($author);
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();

        return $book;
    }

    /**
     * Находит книги с именем, похожим на переданное.
     * @param $title
     * @return iterable|Book[]
     */
    public function findByTitleLike($title): iterable
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Статистика по количеству книг по дням календаря
     * @return iterable
     */
    public function getCreationDateStats(): iterable
    {
        $connection = $this->getEntityManager()->getConnection();

        return $connection->fetchAll('
            SELECT created_at::date as date, COUNT(*) AS cnt
            FROM book
            GROUP BY created_at::date
            ORDER BY created_at::date DESC
        ');
    }
}
