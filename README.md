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
## Mail setup
```php
// .env file
MAIL_MAILER=smtp
MAIL_HOST=send.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=//your username
MAIL_PASSWORD=// your password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME=//your app name
```
## Running the project
```bash
npm run dev
```
or 
```bash
npm run watch
```

```bash
php artisan serve
```
* #### if the profile picture of the user not apper
```bash
php artisan storage:link
```

### To send the post mail list through artisan command
```bash
php artisan mail:send example@gmail.com 
```

```bash
php artisan mail:send example1@gmail.com example2@gmail.com example3@gmail.com
```

* #### if the mail sending error occur run
```bash
php artisan storage:link
```