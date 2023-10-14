    <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

    <p align="center">
    <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
    </p>

    # Information Security Project

    <!-- team members table -->

    |           Nama            |    NRP     |
    | :-----------------------: | :--------: |
    | Andhika Ditya Bagaskara D | 5025201096 |
    |   Ferry Nur Alfian E P    | 5025201214 |
    |     Naufal Faadhilah      | 5025201221 |
    |  Hemakesha Heriqbaldi R   | 5025201209 |

    <!-- end team members table -->

    ## About The Project

    We are using Laravel Framework to build this project. This project is a web application that can be used to store and manage your passwords. This is made for our project in Information Security course.

    The method that we use to encrypt the file are AES, DES, and RC4. The mode that we use are CBC, CFB, OFB, and CTR.

    ## The Flow

    1. User register and login to the web application.
    2. User choose the encryption method and mode that they want to use.
    3. User add, download, and delete their files.
    4. Each file will be encrypted and decrypted using the method and mode that user choose.
    5. Each process will require a password that user input.

    ## Something To Note

    ### File

    -   Handled Filetype: .pdf, .docx, .xls, .xlsx, .jpg, .jpeg, .png, .mp4
    -   Maximum Filesize: 2 MB
    -   In order to increase filesize, you can change the value of `upload_max_filesize` and `post_max_size` in `php.ini` file.

    ### Password

    -   Password for each file is the same as the account password.

    ## Built With

    <!-- make it double columns, centered -->

    | :Frontend: | :Backend: | :Database: | :Server: | :Encryption: | :Encryption Mode: |
    | :--------: | :-------: | :--------: | :------: | :----------: | :---------------: |
    | Bootstrap  |  Laravel  |   MySQL    |  XAMPP   |     AES      |        CBC        |
    |   JQuery   |    PHP    |            |          |     DES      |        CFB        |
    | FontBunny  |           |            |          |     RC4      |        OFB        |
    |            |           |            |          |              |        CTR        |

    ## License

    The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

    ## Installation

```bash
cp .env.example .env
composer install
npm install
php artisan install
```

    ## Running The Project

```bash
php artisan serve
npm run dev
php artisan schedule:work
```

## Seeding Datasets

```bash
php artisan dataset:seed
```
