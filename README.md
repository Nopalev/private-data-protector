# Information Security Project

|           Nama            |    NRP     |
| :-----------------------: | :--------: |
| Andhika Ditya Bagaskara D | 5025201096 |
|   Ferry Nur Alfian E P    | 5025201214 |
|     Naufal Faadhilah      | 5025201221 |
|  Hemakesha Heriqbaldi R   | 5025201209 |

## Table of Contents

-   [About](#about-the-project)
-   [Flow](#the-flow)
-   [Note](#something-to-note)
-   [Tech Stack](#built-with)
-   [Instalation](#installation)
-   [Run](#running-the-project)
-   [Seed](#seeding-datasets)
-   [Justification](#justification)

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

| Frontend  | Backend | Database | Server  | Encryption (phpseclib) | Encryption Mode |
| :-------: | :-----: | :------: | :-----: | :--------------------: | :-------------: |
| Bootstrap | Laravel |  MySQL   | Apache2 |          AES           |       CBC       |
|  JQuery   |   PHP   |          |         |          DES           |       CFB       |
| FontBunny |         |          |         |          RC4           |       OFB       |
|           |         |          |         |                        |       CTR       |

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Installation

```bash
cp .env.example .env
composer install
npm install
php artisan install # run this after setting up your database connection
```

## Running The Project

```bash
php artisan serve
npm run dev
php artisan schedule:work
```

## Seeding Datasets

Seed the database with provided datasets.

```bash
php artisan dataset:seed
```

> Note: please do this on a fresh database

## Justification

### What Are the Things We Considered As Private Data?

We use Indonesian [UU No. 27 Tahun 2022](https://jdih.setkab.go.id/PUUdoc/176837/Salinan_UU_Nomor_27_Tahun_2022.pdf) as a referrence for what is considered as private data. From what are written in the lawbook, we categorize private data into two things: text-based private data (later will be called as biodata, consisting of name, gender, nationality, religion, and mariage status), and file-based private data (such as image of ID card, files containing health report, etc).

We create a database scheme contains:

-   user (has one biodata, has many files)
-   biodata (belong to user)
-   file (belong to user)
-   public key (for generating keys and IVs)

### Key and IV derivation

We use user's password, combined with public keys (different for each user) stored in the database to create keys and IVs that are unique to each users (as long as their passwords are unique). The drawback is, everytime user upload or download any kind of file, the user is required to insert their password, albeit this flow would let us derive unique key and IV but the generated key and IV would always be the same for each user (unless the user change their password).

For this reason, we requiring each users to provide a minimal 8 characters long password, that has at minimum a number, a lowercase, and an uppercase letter in it for security purposes.

#### Key

We concatenate user's password (`P4ssw0Rd` would be `P4ssw0RdP4ssw0Rd`), use the first 16 characters, then for 4 rounds we:

-   XOR the concatenated password with 16 bytes long of public key
-   Shift the generated key leftward once (`P4ssw0RdP4ssw0Rd` would be `4ssw0RdP4ssw0RdP`)
-   For DES algorithm, we would use the first 8 bytes of the derived key

#### IV

We concatenate user's password (`P4ssw0Rd` would be `P4ssw0RdP4ssw0Rd`), use the last 16 characters, then for 4 rounds we:

-   XOR the concatenated password with 16 bytes long of public key
-   Shift the generated IV leftward once (`P4ssw0RdP4ssw0Rd` would be `4ssw0RdP4ssw0RdP`)
-   For DES algorithm, we would use the first 8 bytes of the derived IV

### Analysis

We use several API endpoints that retrieve's users biodata, and files (3 for each users). We create a seeder that seeds 12 users, each with different encryption method and mode, but same biodata and files for all 12 of them.

### Custom Commands

We create several custom commands for tidying up our instalation and do several required jobs.

#### `php artisan install`

run several commands such as:

-   migration
-   generate application key
-   symbolic link

#### `php artisan temp:flush`

In order to send download response with user's file, we decrypt the requested file, save in a temp directory, then sends the file. This command is used to remove those decrypted residual files. This command is automated every 10 seconds by running the `php artisan schedule:work` command.

#### `php artisan dataset:seed`

This command is used to seed the database with datasets used for analyze every combination of encryption methods and modes.

# Information Security Project Phase 2

## Additional Features

### 1. File Pool

contains all files that have been uploaded by users

### 2. File Sharing

users can share their files to other users by requesting the file to be shared, and the owner of the file can accept the request.
Then, the owner of the file get a key to decrypt the file. The key then can be shared to the requested user of the file.

### 3. About the Shared Key

Everyone can request the same file, but may get different key to decrypt the file.
This is can reduce the risk of the file being decrypted by unauthorized user.

#### References

https://github.com/paragonie/halite/blob/master/doc/Basic.md
