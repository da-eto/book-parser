<?php

declare(strict_types=1);

namespace App\Book\Loader;

use App\Book\BookInfo;
use App\Book\Exception\BookLoaderException;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Parser\UniqueNode;
use Prewk\XmlStringStreamer\Stream\File;

/**
 * Загрузчик информации из FB2-файлов.
 * @package App\Book\Loader
 */
class Fb2BookLoader implements BookLoaderInterface
{
    /**
     * Поддерживаемый формат
     */
    private const FORMAT = 'fb2';

    /**
     * Используемый размер чанков при парсинге fb2
     */
    private const CHUNK_SIZE = 16384;

    /**
     * Загружает информацию о книге из файла.
     * @param string $filename путь к файлу книги
     * @return BookInfo
     * @throws BookLoaderException
     */
    public function loadBookInfo(string $filename): BookInfo
    {
        try {
            $stream = new File($filename, self::CHUNK_SIZE);
            $parser = new UniqueNode(['uniqueNode' => 'description']);
            $streamer = new XmlStringStreamer($parser, $stream);
            $nodeData = $streamer->getNode();
        } catch (\Throwable $exception) {
            throw new BookLoaderException(
                sprintf('Файл "%s": Ошибка загрузки информации о книге', $filename),
                0,
                $exception
            );
        }

        if ($nodeData === false) {
            throw new BookLoaderException(sprintf('Файл "%s": Не найден элемент description', $filename));
        }

        $description = new \SimpleXMLElement($nodeData, LIBXML_NOERROR);
        $bookTitleNodes = $description->xpath('title-info/book-title');
        $langNodes = $description->xpath('title-info/lang');

        if (count($bookTitleNodes) !== 1) {
            throw new BookLoaderException(sprintf('Файл "%s": Не найден элемент book-title', $filename));
        }

        if (count($langNodes) !== 1) {
            throw new BookLoaderException(sprintf('Файл "%s": Не найден элемент lang', $filename));
        }

        $title = (string) $bookTitleNodes[0];
        $lang = (string) $langNodes[0];

        $authorNodes = $description->xpath('title-info/author');
        $author = '';

        foreach ($authorNodes as $authorNode) {
            $firstNameNodes = $authorNode->xpath('first-name');
            $lastNameNodes = $authorNode->xpath('last-name');
            $middleNameNodes = $authorNode->xpath('middle-name');

            if (count($firstNameNodes) !== 1 || count($lastNameNodes) !== 1) {
                continue;
            }

            $nameParts = [(string) $firstNameNodes[0]];

            if (count($middleNameNodes) > 0) {
                $nameParts[] = (string) $middleNameNodes[0];
            }

            $nameParts[] = (string) $lastNameNodes[0];
            $author = implode(' ', $nameParts);

            break;
        }

        if ($author === '') {
            throw new BookLoaderException(
                sprintf('Файл "%s": Не найдено ни одного автора с указанными именем и фамилией', $filename)
            );
        }

        return new BookInfo($title, $author, $lang);
    }

    /**
     * Поддерживает ли заданный формат книг?
     * @param string $format формат
     * @return bool
     */
    public function supports(string $format): bool
    {
        return mb_strtolower($format) === self::FORMAT;
    }
}
