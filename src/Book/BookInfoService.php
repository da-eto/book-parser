<?php

declare(strict_types=1);

namespace App\Book;

use App\Book\Exception\InvalidArgumentBookException;
use App\Book\Exception\LogicBookException;
use App\Book\Loader\BookLoaderInterface;

class BookInfoService
{
    /**
     * @var iterable|BookLoaderInterface[]
     */
    private $loaders;

    public function __construct(iterable $loaders)
    {
        foreach ($loaders as $loader) {
            if (!$loader instanceof BookLoaderInterface) {
                throw new InvalidArgumentBookException(sprintf(
                    'Массив $loaders должен содержать только реализации BookLoaderInterface, передан: "%s"',
                    get_class($loader)
                ));
            }
        }

        $this->loaders = $loaders;
    }

    /**
     * Получает информацию о книге из файла.
     * @param string $filename путь до файла
     * @return BookInfo
     */
    public function loadBookInfo(string $filename): BookInfo
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentBookException(sprintf('Файл "%s" не существует', $filename));
        }

        $format = $this->guessFormat($filename);
        $loader = $this->getLoader($format);

        if ($loader === null) {
            throw new LogicBookException(sprintf(
                'Не найден загрузчик формата "%s" для файла "%s"',
                $format,
                $filename
            ));
        }

        return $loader->loadBookInfo($filename);
    }

    /**
     * Возвращает формат книги по имени файла.
     * @param string $filename
     * @return string
     */
    private function guessFormat(string $filename): string
    {
        return mb_strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * Возвращает загрузчик для формата.
     * @param string $format
     * @return BookLoaderInterface|null
     */
    private function getLoader(string $format): ?BookLoaderInterface
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($format)) {
                return $loader;
            }
        }

        return null;
    }
}
