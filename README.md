## Парсинг книг

Простое бекенд приложение на PHP с использованием Symfony для парсинга .epub и fb2 файлов.

### Установка и настройка

Запуск dev-среды:
```shell script
$ docker-compose up -d
``` 

Для локальной настройки (например, БД) можно скопировать файл `.env` в `.env.local` и изменить переменные.

Запускаем сессию `bash` в контейнере приложения:
```shell script
$ docker-compose exec app bash
```

Далее все команды, начинающиеся с `root@app`, выполняются в докер-контейнере `app`.
При запуске на машине разработчика на этом месте будет приветствие вида `root@070b69e285a3` с ID контейнера.

Установка зависимостей, создание БД:
```shell script
root@app:/var/www# composer install
root@app:/var/www# php bin/console doctrine:migrations:migrate
```

### Запуск команд

Добавление книг в библиотеку:
```shell script
root@app:/var/www# php bin/console book:load data/books/light.epub
root@app:/var/www# php bin/console book:load data/books/alice.fb2
```

Поиск книги по названию:
```shell script
root@app:/var/www# php bin/console book:search Alice
```

Сводная статистика по библиотеке:
```shell script
root@app:/var/www# php bin/console book:stats
```
