<?php

declare(strict_types=1);

namespace App\Book\DTO;

/**
 * Информация об авторе.
 * @package App\Book
 */
class AuthorInfo
{
    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $middleName;

    public function __construct(string $firstName, string $lastName, string $middleName = '')
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middleName;
    }
}
