
# Команда проекта
* Петрушов Александр 1121б
* Малых Кирилл 1121б
* Розмахов Илья 1121б
* Плосков Артур 1521б

Stack: 
* Laravel 11
* tailwind css
* laravel blade templates
* alpine js



## Как накатить проект? 

PHP 8.2 REQUIRED

Клонировать репозиторий

    git clone https://github.com/ElephantCratos/food-deliv.git

Поменять на ветку репозитория 

    cd food-deliv

Поднять докер 

    docker compose up -d 

Копировать .env.example файлик и заполнить в новый файл .env. Под себя требуется поменять DATABASE поля  

  * DB_CONNECTION=pgsql
  * DB_HOST=db
  * DB_PORT=5432
  * DB_DATABASE=refactorian
  * DB_USERNAME=refactorian
  * DB_PASSWORD=refactorian

Перед тем как писать все команды ниже зайти в контейнер

    docker compose exec php bash 

Установить все зависимости при помощи композера

    composer install 
    
Запустить миграции и сидеры ( env файл перед этим шагом мастхев!!!)

    php artisan migrate -seed

Теперь проект должен работать.

*

## *СПЕЦИАЛЬНО ДЛЯ АРТУРА НЕ ВСЕ ОТСЮДА НАДО ЗАПУСКАТЬ В ДОКЕРЕ*

*
**Must Have Commands to start**

    git clone https://github.com/ElephantCratos/food-deliv.git
    cd food-deliv
    composer install
    php artisan migrate -seed
 
    
**OOOPS something went wrong commands**
        
     php artisan config:clear
     php artisan cache:clear
     php artisan route:clear
     npm i - не ДОКЕР
     npm run dev - не ДОКЕР
     php artisan migrate:fresh -seed

    
