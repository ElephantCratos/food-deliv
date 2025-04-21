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

Установить все зависимости при помощи композера

    composer install 

Копировать .env.example файлик и заполнить в новый файл .env. Под себя требуется поменять DATABASE поля  

  * DB_CONNECTION=mysql
  * DB_HOST=127.0.0.1
  * DB_PORT=3306
  * DB_DATABASE=food-delivery
  * DB_USERNAME=root
  * DB_PASSWORD=

Запустить миграции и сидеры ( env файл перед этим шагом мастхев!!!)

    php artisan migrate -seed

Запустить локальный сервер (OSPanel, artisan serve)

    php artisan serve (как пример)

Теперь проект должен работать.

**Must Have Commands to start**

    git clone https://github.com/ElephantCratos/food-deliv.git
    cd food-deliv
    composer install
    php artisan migrate -seed
 
    
**OOOPS something went wrong commands**
        
     php artisan config:clear
     php artisan cache:clear
     php artisan route:clear
     npm i
     npm run dev
     php artisan migrate:fresh -seed

    
