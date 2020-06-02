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
root@app:/var/www# php bin/console doctrine:database:create
```

