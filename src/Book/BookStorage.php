<?php

declare(strict_types=1);

namespace App\Book;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;

/**
 * Сохранение и получение данных о книгах в библиотеке.
 * @package App\Book
 */
class BookStorage
{
    /**
     * @var BookRepository
     */
    private $bookRepository;
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(BookRepository $bookRepository, AuthorRepository $authorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
     * Сохраняет информацию о книге в библиотеку.
     * @param BookInfo $bookInfo
     * @return Book
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function storeBook(BookInfo $bookInfo): Book
    {
        $author = $this->authorRepository->findOneByFullName($bookInfo->getAuthor());

        if ($author === null) {
            $author = $this->authorRepository->createAuthor($bookInfo->getAuthor());
        }

        $book = $this->bookRepository->findOneByTitleLangAuthor($bookInfo->getTitle(), $bookInfo->getLang(), $author);

        if ($book === null) {
            $book = $this->bookRepository->createBook($bookInfo->getTitle(), $bookInfo->getLang(), $author);
        }

        return $book;
    }

    /**
     * @param $title
     * @return iterable|Book[]
     */
    public function searchBooks($title): iterable
    {
        return $this->bookRepository->findByTitleLike($title);
    }

    /**
     * Возвращает имена авторов без произведений.
     * @return array
     */
    public function getAuthorsWithoutBooksStats(): array
    {
        $stats = [];
        $authors = $this->authorRepository->findAuthorsWithoutBooks();

        foreach ($authors as $author) {
            $stats[] = $author->getFullName();
        }

        return $stats;
    }

    /**
     * Возвращает статистику по авторам с произведениями в библиотеке и количеству произведений каждого
     * @return array
     */
    public function getAuthorsAndBooksCountStats(): array
    {
        $stats = [];
        $authorsAndCount = $this->authorRepository->findAuthorsAndBooksCount();

        foreach ($authorsAndCount as list($author, $count)) {
            $stats[$author->getFullName()] = $count;
        }

        return $stats;
    }

    /**
     * Возвращает статистику по количеству загруженных книг в каждый день, когда они загружались
     * @return array
     */
    public function getBooksCalendarStats(): array
    {
        $stats = [];
        $datesAndCount = $this->bookRepository->getCreationDateStats();

        foreach ($datesAndCount as $row) {
            $stats[$row['date']] = $row['cnt'];
        }

        return $stats;
    }
}
