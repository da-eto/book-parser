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
}
