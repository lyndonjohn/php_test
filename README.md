# PHP test

## 1. Installation

  - create an empty database named "phptest" on your MySQL server
  - import the dbdump.sql in the "phptest" database
  - put your MySQL server credentials in the constructor of DB class
  - you can test the demo script in your shell: "php index.php"

## 2. Expectations

This simple application works, but with very old-style monolithic codebase, so do anything you want with it, to make it:

  - easier to work with
  - more maintainable

## 3. Repository Installation
    1. Clone repository to your local machine. (git@github.com:lyndonjohn/php_test.git)
    2. Run `composer install` to install dependencies (optional)
    3. Open terminal, `cd` to project folder and run `php index.php`

## 4. How to use PHP Code Sniffer
    1. Open terminal, run `phpcs -s --standard=PSR12 src`
    2. Blank result means all classes codes inside src directory are in accordance to PSR12 standard.
    3. Run `phpcbf --standard=PSR12 src` to fix all errors.
    4. Not all errors will be fixed by `phpcbf --standard=PSR12 src`, so you have to fix them manually.
