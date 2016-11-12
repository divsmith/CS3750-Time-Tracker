# README #

Volunteer Hours Tracking

### What is this repository for? ###

This repository is for the source control of Volunteer Hours Tracking. Our initial release is planned to be v1.1. It will be based on PHP, JavaScript, HTML5, JQuery, and Bootstrap. For a back-end database we will be using MySql. This is going to be designed to run on WAMP.

### Technologies/Libraries ###
* Base template on Initializr
* HTML5
* JavaScript
* JQuery
* BootStrap
* PHP
* MySQL
* Composer
* Targeted for WAMP or LAMP

### How do I get set up? ###

* Summary of set up
* Configuration
* Dependencies
* Database configuration
* How to run tests
* Deployment instructions

### Contribution guidelines ###

This project is designed for WAMPServer x86. Install the latest version from:
https://sourceforge.net/projects/wampserver/files/latest/download

Alternatively, if you are running Windows or MacOS, install Apache, MySql, PHP individually.

The gulp file expects the Apache directory to be:
/wamp/www

This should work on either Windows or Linux as long as the directory is the same. The gulpfile watches will automatically copy the files into that directory
if they change. This allows a fluent dev experience.


SweetAlert2 - Alert modal library - https://limonte.github.io/sweetalert2/

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* Repo owner or admin
* Other community or team contact

### Composer Install

After you clone this repo you will need to install the dependency packages for it to work.
Composer is used to do this.
When you clone this repo you'll have a composer.json file which hold all the dependencies required.
in the Directory that holds the composer.json type the following in your terminal(console)
composer install 
This will find the composer.json and install the packages listed in that file.
(if you don't have composer installed please follow the directions here. 
https://getcomposer.org/
Press the Download button and follow the instructions for your Operating System.

### PHP Development

For those who may not be familiar with setting up an WAMP server php 5.6 allows us to spin up a local instance.
In the Terminal or Console navigate to the index.php file and in that Directory enter the following command ito start a local php web server php -S localhost:8000(8000 is just the port number you can change this to your hearts content).
Now navigate to localhost:8000 and you will be able to request the routes specified in the index.php

There are other services you can use if you would like to set up a full Apache web server click here https://www.apachefriends.org/download.html. If you need additional help installing or setting up your local server please search in google for " 'Your operating system' xampp installation guide". 

* [Learn Markdown](https://bitbucket.org/tutorials/markdowndemo)
