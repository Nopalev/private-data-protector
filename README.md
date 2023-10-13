<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Keamanan Informasi

<!-- team members table -->

| Nama                      | NRP        |
| ------------------------- | ---------- |
| Andhika Ditya Bagaskara D | 5025201096 |
| Ferry Nur Alfian E P      | 5025201214 |
| Naufal Faadhilah          | 5025201221 |
| Hemakesha Heriqbaldi R    | 5025201209 |

<!-- end team members table -->

## About The Project

We are using Laravel Framework to build this project. This project is a web application that can be used to store and manage your passwords. This is made for our project in Information Security course.

The method that we use to encrypt the file are AES, DES, and RC4. The mode that we use are CBC, CFB, OFB, and CTR.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Installation

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan app:key_generate
php artisan storage:link
npm install
```

## Running The Project

```bash
php artisan serve
npm run dev
```
