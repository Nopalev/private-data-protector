# Information Security Project

|           Nama            |    NRP     |
| :-----------------------: | :--------: |
| Andhika Ditya Bagaskara D | 5025201096 |
|   Ferry Nur Alfian E P    | 5025201214 |
|     Naufal Faadhilah      | 5025201221 |
|  Hemakesha Heriqbaldi R   | 5025201209 |

## Table of Contents

- [About](#about-the-project)
- [Flow](#the-flow)
- [Note](#something-to-note)
- [Tech Stack](#built-with)
- [Instalation](#installation)
- [Run](#running-the-project)
- [Seed](#seeding-datasets)

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

- Handled Filetype: .pdf, .docx, .xls, .xlsx, .jpg, .jpeg, .png, .mp4
- Maximum Filesize: 2 MB
- In order to increase filesize, you can change the value of `upload_max_filesize` and `post_max_size` in `php.ini` file.

### Password

- Password for each file is the same as the account password.

## Built With

<!-- make it double columns, centered -->

| :Frontend: | :Backend: | :Database: | :Server: | :Encryption: | :Encryption Mode: |
| :--------: | :-------: | :--------: | :------: | :----------: | :---------------: |
| Bootstrap  |  Laravel  |   MySQL    | Apache2  |     AES      |        CBC        |
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

> Note: please do this on a fresh database
