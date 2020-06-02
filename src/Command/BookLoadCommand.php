<?php

namespace App\Command;

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

        // TODO: заменить заглушку на работу с файлом
        if (!file_exists($path)) {
            $io->error(sprintf('Файл "%s" не найден.', $path));
        }

        $io->success('Книга успешно загружена.');

        return 0;
    }
}
