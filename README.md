# Bulletin Board

Bulletin board is the first basic program of laravel and vue training.
It includes basic CRUD, laravel sanctum authentication, and Excel Upload/Download. 

## Description
* #### php version 7.4.33
* #### laravel version 8
* #### vue js version 2
## Installation

```bash
git clone https://github.com/PhyoeZawAungSCM/Bulletin_board.git
```

```bash
cd Bulletin_board
```
```bash
composer install
```
```bash
copy .env.example .env
```
#### create a database and connect that database
```php
#in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your table name
DB_USERNAME=your database user name
DB_PASSWORD=your database user password
```
```bash
php artisan migrate
```
```bash
php artisan key:generate
```
```bash
npm install
```
```bash
php artisan db:seed --class=Admin
```
```bash
php artisan storage:link
```
## Running the project
```bash
npm run dev
```
or 
```bash
npm run watch
```

```
php artisan serve