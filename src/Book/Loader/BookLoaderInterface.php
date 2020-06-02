<?php

declare(strict_types=1);

namespace App\Book\Loader;

use App\Book\BookInfo;

/**
 * Интерфейс загрузчика информации о книге.
 * @package App\Book\Loader
 */
interface BookLoaderInterface
{
    /**
     * Поддерживает ли заданный формат книг?
     * @param string $format формат
     * @return bool
     */
    public function supports(string $format): bool;

    /**
     * Загружает информацию о книге из файла.
     * @param string $filename путь к файлу книги
     * @return BookInfo
     */
    public function loadBookInfo(string $filename): BookInfo;
}
