<?php

declare(strict_types=1);

namespace App\Book\Exception;

/**
 * Недопустимый аргумент.
 * @package App\Book\Exception
 */
class InvalidArgumentBookException extends LogicBookException implements BookExceptionInterface
{
}
