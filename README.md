<div align="center">
  <h2><b>🕹️🕹️ Laravel API🕹️🕹️</b></h2>
  <br/>
</div>

<a name="readme-top"></a>

<!-- TABLE OF CONTENTS -->

# 📗 Table of Contents

- [📖 About the Project](#about-project)
    - [🛠 Built With](#built-with)
        - [Tech Stack](#tech-stack)
    - [🚀 Links](#api-docs)
    - [Features](#features)
- [💻 Getting Started](#getting-started)
    - [Setup](#setup)
    - [Prerequisites](#prerequisites)
    - [Usage](#usage)
    - [Swagger](#swagger)
- [🤝 Contributing](#contributing)

<!-- PROJECT DESCRIPTION -->

# 📖  API <a name="about-project"></a>

**[Idara API]** is a boilerplate for building API applications using Laravel 11, incorporating Domain-Driven Design (DDD), role-based authentication, and various other features.

### Tech Stack <a name="tech-stack"></a>

- <a href="https://www.php.net/">PHP</a>
- <a href="https://laravel.com/">Laravel</a>

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- Link to Api Documentation -->

## 🚀 Links <a name="api-docs"></a>

To access the documentation goto the below link

- Link to api routes
```
http://localhost:8000/v1
```
- Link to documentation
```
http://localhost:8000/v1/documentation
http://localhost:8000/docs
```

<br/>

<p align="right">(<a href="#readme-top">back to top</a>)</p>

<!-- GETTING STARTED -->

## 💻 Getting Started <a name="getting-started"></a>

To get a local copy up and running, follow these steps.

### Prerequisites

In order to run this boilerplate, you need:

1. PHP ^8.2 <br>
   use the following link to setup `PHP` if you dont have it already installed on your computer
<p align="left">(<a href="https://www.php.net/manual/en/install.php">install PHP</a>)</p>

2. Composer <br>
   use the following link to Download `Composer` if you dont have it already installed on your computer
<p align="left">(<a href="https://getcomposer.org/download/">install Composer</a>)</p>

## Install

Install dependencies:

```
composer install
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

If using uuid then changes in migration.
Kindly check the below link for that.
```
https://spatie.be/docs/laravel-permission/v6/advanced-usage/uuid
```

## Setup


create .env file, change using the .env.example file and update the Database, Google Oauth (optional), and Email credentials.
```
cp .env.example .env
```

Generate keys, Run the migration and seed roles:

```
php artisan key:generate 
php artisan migrate --seed
```

### Usage

The following command can be used to run the application.

```sh
  php artisan serve
```

### Swagger

Then Generate the Swagger documentation by running the following command:.

```sh
  php artisan l5-swagger:generate
```

<p align="right">(<a href="#readme-top">back to top</a>)</p>
