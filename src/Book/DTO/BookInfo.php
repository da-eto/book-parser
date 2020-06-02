<?php

declare(strict_types=1);

namespace App\Book\DTO;

/**
 * Информация о книге.
 * @package App\Book
 */
class BookInfo
{
    /** @var string */
    private $title;

    /** @var string */
    private $lang;

    /** @var array|AuthorInfo[] */
    private $authors;

    public function __construct(string $title, string $lang, iterable $authors)
    {
        $this->title = $title;
        $this->lang = $lang;
        $this->authors = $authors;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @return AuthorInfo[]|array
     */
    public function getAuthors()
    {
        return $this->authors;
    }
}
