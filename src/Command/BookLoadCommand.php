<?php

declare(strict_types=1);

namespace App\Command;

use App\Book\BookInfoService;
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
     * @var BookInfoService
     */
    private $bookInfoService;

    public function __construct(BookInfoService $bookInfoService)
    {
        $this->bookInfoService = $bookInfoService;

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
        $bookInfo = $this->bookInfoService->loadBookInfo($path);

        // TODO: записывать данные в БД вместо вывода инфы
        var_dump($bookInfo);

        $io->success('Книга успешно загружена.');

        return 0;
    }
}
