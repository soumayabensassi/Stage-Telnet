# E-commerce-space
## Table of contents
* [Install PHPStorm](#install-PHPStorm)
* [Install wampp](#install-wampp)
* [Install node.js](#install-node.js)
* [Install Composer](#install-Composer)
* [Clone  Project](#clone-Project)
* [Create Vendor](#create-Vendor)
* [Create DataBase](#create-DataBase)
* [Install python](#install-python)
* [Install the requirement of python](#install-the-requirement-of-python)
* [Run the project](#run-the-project)

## Install PHPStorm
https://www.jetbrains.com/shop/eform/students
## Install wampp
https://www.wampserver.com
https://wampserver.aviatechno.net/
## Install Composer
https://getcomposer.org/download/
## Install Node.js
https://nodejs.org/fr/download/
## Clone  Project
* Open Cmd
```
$ git clone https://github.com/MaamouriAmine/E-commerce-space.git
```
## change line 30 in .env file
DATABASE_URL="mysql://username:password@127.0.0.1:3306/nom_base_donnee"
## Create Vendor of the project
* open the Terminal of the project and run this command 
```
$composer update
```
* open the Terminal of the project and run these commands 
```
$ npm install
$ npm run build or npm run watch 
```
## Create DataBase

* To create database open the project with PHPStorm then open the Terminal

```
$ php bin/console doctrine:database:create
$ php bin/console make:migration 
$ php bin/console doctrine:migrations:migrate
```
* Then open phpmyadmin and run this command in SQL of Database:
alter table donnee CHANGE `timestamp` `timestamp` DateTime(6)
## Install python
https://python.fr.uptodown.com/windows/telecharger
## Install the requirement of python
```
$pip install -r requirements.txt
```
* Then open E-commerce-space\FirstProject\src\Security\LoginAuthenticator.php in line 59 change path of your file 
* Then open E-commerce-space\python\producer.py in line 8 and line 25  change path of your file 

## Run the project 
* To run the project open Terminal and run this command to activate emails
```
$ php bin/console messenger:consume async -vv
```
*Then open other Terminal and run thid command 
```
$ php bin/console server:run
```

