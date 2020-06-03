<?php

namespace App\Command;

use App\Book\BookStorage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Сводная статистика по библиотеке
 * @package App\Command
 */
class BookStatsCommand extends Command
{
    protected static $defaultName = 'book:stats';
    /**
     * @var BookStorage
     */
    private $bookStorage;

    public function __construct(BookStorage $bookStorage)
    {
        $this->bookStorage = $bookStorage;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Сводная статистика по библиотеке');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $withBooks = $this->bookStorage->getAuthorsAndBooksCountStats();

        if (count($withBooks) > 0) {
            $io->writeln('Авторы с произведениями в библиотеке:');
            $io->table(['Полное имя автора', 'Количество книг'], array_map(function ($name, $count) {
                return [$name, $count];
            }, array_keys($withBooks), $withBooks));
        } else {
            $io->writeln('Авторы с произведениями в библиотеке не найдены.');
        }

        $withoutBooks = $this->bookStorage->getAuthorsWithoutBooksStats();

        if (count($withoutBooks) > 0) {
            $io->writeln('Авторы без произведений в библиотеке:');
            $io->table(['Полное имя автора'], array_map(function ($name) {
                return [$name];
            }, $withoutBooks));
        } else {
            $io->writeln('Авторы без произведений в библиотеке не найдены.');
        }

        $booksCalendar = $this->bookStorage->getBooksCalendarStats();

        if (count($booksCalendar) > 0) {
            $io->writeln('Количество загруженных книг в каждый календарный день:');
            $io->table(['Дата', 'Количество книг'], array_map(function ($date, $count) {
                return [$date, $count];
            }, array_keys($booksCalendar), $booksCalendar));
        } else {
            $io->writeln('В библиотеке нет загруженных книг.');
        }

        return 0;
    }
}
