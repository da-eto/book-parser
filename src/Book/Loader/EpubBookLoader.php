<?php

declare(strict_types=1);

namespace App\Book\Loader;

use App\Book\BookInfo;
use App\Book\Exception\BookLoaderException;
use lywzx\epub\EpubParser;

/**
 * Загрузчик информации о книге из EPUB-файла.
 * @package App\Book\Loader
 */
class EpubBookLoader implements BookLoaderInterface
{
    /**
     * Поддерживаемый формат
     */
    private const FORMAT = 'epub';

    /**
     * Поддерживает ли заданный формат книг?
     * @param string $format формат
     * @return bool
     */
    public function supports(string $format): bool
    {
        return mb_strtolower($format) === self::FORMAT;
    }

    /**
     * Загружает информацию о книге из файла.
     * @param string $filename путь к файлу книги
     * @return BookInfo
     * @throws BookLoaderException
     */
    public function loadBookInfo(string $filename): BookInfo
    {
        try {
            $parser = new EpubParser($filename);
            $parser->parse();
        } catch (\Throwable $exception) {
            throw new BookLoaderException(
                sprintf('Файл "%s": Ошибка загрузки информации о книге', $filename),
                0,
                $exception
            );
        }

        $title = $parser->getDcItem('title');

        if ($title === false) {
            throw new BookLoaderException(sprintf('Файл "%s": Не определено название', $filename));
        }

        $author = $parser->getDcItem('creator');

        if ($author === false) {
            throw new BookLoaderException(sprintf('Файл "%s": Не определен автор', $filename));
        }

        $lang = $parser->getDcItem('language');

        if ($lang === false) {
            throw new BookLoaderException(sprintf('Файл "%s": Не определен язык', $filename));
        }

        return new BookInfo($title, $author, $lang);
    }
}
