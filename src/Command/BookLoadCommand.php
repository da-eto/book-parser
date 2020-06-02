<?php

namespace App\Command;

use Prewk\XmlStringStreamer;
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

        if (!file_exists($path)) {
            $io->error(sprintf('Файл "%s" не найден.', $path));

            return 1;
        }

        $streamer = XmlStringStreamer::createUniqueNodeParser($path, ['uniqueNode' => 'description']);
        $nodeData = $streamer->getNode();

        if ($nodeData === false) {
            $io->error(sprintf('Файл "%s" не содержит элемент description.', $path));

            return 1;
        }

        $description = new \SimpleXMLElement($nodeData, LIBXML_NOERROR);

        $bookTitleNodes = $description->xpath('title-info/book-title');

        if (count($bookTitleNodes) !== 1) {
            $io->error('Не указано название');

            return -1;
        }

        $bookTitle = (string) $bookTitleNodes[0];
        $io->writeln('Название: ' . $bookTitle);


        $langNodes = $description->xpath('title-info/lang');

        if (count($langNodes) !== 1) {
            $io->error('Не указан язык');

            return -1;
        }

        $lang = (string) $langNodes[0];
        $io->writeln('Язык: ' . $lang);

        $authors = $description->xpath('title-info/author');

        foreach ($authors as $author) {
            $firstNameNodes = $author->xpath('first-name');
            $lastNameNodes = $author->xpath('last-name');
            $middleNameNodes = $author->xpath('middle-name');
            $nickNameNodes = $author->xpath('nickname');

            if ((count($firstNameNodes) !== 1 || count($lastNameNodes) !== 1) && count($nickNameNodes) === 0) {
                $io->error('Не могу прочитать информацию об авторе');

                return -1;
            }

            if (count($firstNameNodes) === 1 && count($lastNameNodes) === 1) {
                if (count($middleNameNodes) > 0) {
                    $authorName = implode(' ', [
                        (string) $firstNameNodes[0],
                        (string) $middleNameNodes[0],
                        (string) $lastNameNodes[0],
                    ]);
                } else {
                    $authorName = implode(' ', [
                        (string) $firstNameNodes[0],
                        (string) $lastNameNodes[0],
                    ]);
                }
            } else {
                $authorName = (string) $nickNameNodes[0];
            }

            $io->writeln('Автор: ' . $authorName);
        }

        $io->success('Книга успешно загружена.');

        return 0;
    }
}
