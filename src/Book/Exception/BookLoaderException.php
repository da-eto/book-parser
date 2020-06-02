<?php

declare(strict_types=1);

namespace App\Book\Exception;

/**
 * Ошибка загрузчика информации о книге.
 * @package App\Book\Exception
 */
class BookLoaderException extends \Exception implements BookExceptionInterface
{
}
