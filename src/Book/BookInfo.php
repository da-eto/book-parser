<?php

declare(strict_types=1);

namespace App\Book;

/**
 * Информация о книге.
 * @package App\Book
 */
class BookInfo
{
    /** @var string */
    private $title;

    /** @var string */
    private $author;

    /** @var string */
    private $lang;

    public function __construct(string $title, string $author, string $lang)
    {
        $this->title = $title;
        $this->author = $author;
        $this->lang = $lang;
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
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }
}
