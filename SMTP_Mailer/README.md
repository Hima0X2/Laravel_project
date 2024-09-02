# Laravel SMTP_Mailer

Mail send using Laravel.

## Installation

Clone the repository-

```
git clone https://github.com/Hima0X2/Laravel_project.git
```

Then cd into the folder with this command-

```
cd SMTP_Mailer
```

Then do a composer install

```
composer install
```

Then do a npm install

```
npm install
```

Then create a environment file using this command-

```
cp .env.example .env
```

Then edit `.env` file with appropriate credential for your Mailer. 
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=yourmail@gmail.com
MAIL_PASSWORD=yourpassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="yourmail@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Run server

Run server using this command-

```
php artisan serve
```

Then go to `http://localhost:8000` from your browser and see the app.

