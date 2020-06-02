<?php

declare(strict_types=1);

namespace App\Command;

use App\Book\BookParser;
use App\Book\BookStorage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда загрузки информации о книге из файла.
 * @package App\Command
 */
class BookLoadCommand extends Command
{
    protected static $defaultName = 'book:load';

    /**
     * @var BookParser
     */
    private $bookParser;
    /**
     * @var BookStorage
     */
    private $bookStorage;

    public function __construct(BookParser $bookParser, BookStorage $bookStorage)
    {
        $this->bookParser = $bookParser;
        $this->bookStorage = $bookStorage;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Загружает книгу из файла и читает мета-информацию (название, автор, язык)')
            ->addArgument('path', InputArgument::REQUIRED, 'Путь до файла книги');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('path');

        $bookInfo = $this->bookParser->loadBookInfo($path);
        $io->success('Информация о книге загружена из файла');

        $io->table(['Название', 'Автор', 'Язык'], [[
            $bookInfo->getTitle(),
            $bookInfo->getAuthor(),
            $bookInfo->getLang(),
        ]]);

        $this->bookStorage->storeBook($bookInfo);
        $io->success('Информация о книге добавлена в библиотеку');

        return 0;
    }
}
