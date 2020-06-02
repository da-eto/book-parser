<?php

declare(strict_types=1);

namespace App\Book\Exception;

/**
 * Логическая ошибка (неправильное использование классов и подобное).
 * @package App\Book\Exception
 */
class LogicBookException extends \LogicException implements BookExceptionInterface
{
}
