<?php

namespace App\Command;

use App\Book\BookStorage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда поиска книги по названию
 * @package App\Command
 */
class BookSearchCommand extends Command
{
    protected static $defaultName = 'book:search';
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
            ->setDescription('Поиск книги по названию')
            ->addArgument('title', InputArgument::REQUIRED, 'Название книги');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $title = $input->getArgument('title');
        $booksData = [];
        $books = $this->bookStorage->searchBooks($title);

        foreach ($books as $book) {
            $booksData[] = [
                $book->getTitle(),
                $book->getLang(),
                $book->getAuthor()->getFullName(),
            ];
        }

        if (count($booksData) > 0) {
            $io->writeln('Найдены книги:');
            $io->table(['Название', 'Язык', 'Автор'], $booksData);
        } else {
            $io->writeln('Книги не найдены');
        }

        return 0;
    }
}
