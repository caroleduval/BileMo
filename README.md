BileMo
======

A Symfony project created on January 15, 2018, 2:15 pm.

Purpose : Creating an API Rest for B to Business of luxurious mobilphones.
As anonymous, you can access :
- a documentation page (BileMo\api\doc)
When connected as user (user with ROLE_USER), you can :
- see the list of available phones
- see the details of one phone
When connected as client's admin (user with ROLE_ADMIN), you can :
- see the list of the users of this client
- see the details of one user of this client
- add a user as a user of this client
- delete a user of this client

# Configuration
Symfony 3.4
php     7.1.12
MySQL   5.6.38


# Download the project from github on your computer
- within zip format on `https://github.com/caroleduval/BileMo`
- via the console :
    `git clone https://github.com/caroleduval/BileMo.git`

# Install the projet with the console
- browse to the directory that contains the project.
- Run `composer update` and define your own values when asked.

# Fill the database with datas
- Run : `php bin/console test:initialize-BM`
- open [http://localhost/BileMo/web/app_dev.php] on your Postman

It's now OK !

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/e36bfa0c493446c8b12b72944184440e)](https://www.codacy.com/app/caroleduval/BileMo?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=caroleduval/BileMo&amp;utm_campaign=Badge_Grade)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/caroleduval/BileMo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/caroleduval/BileMo/?branch=master)