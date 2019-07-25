# symfony-export-csv
Using Symfony framework to download JSON File, insert to database, and export summary to CSV File

## Installation

Clone this repository to your project and run composer to get all dependency library

```bash
git clone git@github.com:pardosa/exportorder.git myproject
cd myproject
composer update
```
Don't forget to put your username and password for gmail smtp login in .nev file

```bash
MAILER_URL=gmail://username:password@127.0.0.1?encryption=tls&auth_mode=oauth
```

## Usage

To Download JSON File, import to database, and export report to CSV:
```bash
php bin\console app:export-order "output file name"
```
To Send the CSV File to email:

```bash
php bin\console app:send-report "email recipient" "output file name"
```
## License
[MIT](https://choosealicense.com/licenses/mit/)