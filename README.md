## Setup Insturctions

- Use PHP 8.2 or XAMPP 8.2 for your Windows Environment
- Create a hosts file entry for virtual host as 127.0.0.1 news-aggregator.test
- Create a virtual host entry in xampp/apache/conf/extra/httpd-vhosts.conf file
- Clone project using SSH/HTTPS: git clone git@github.com:alinawaz254/news-aggregator.git
- Run: composer install to install the Laravel framework and its dependencies
- Create an empty MySQL database and copy .env.example file to .env file and setup your DB credentials
- Run: php artisan migrate

## API Documentation

Generate Swagger based API documentation via this command: php artisan l5-swagger:generate
API documentation can be accessed via virtual host URL at http://news-aggregator.test/api/documentation

## Fetch News from News APIs

Fetch news APIs via command: php artisan fetch-news

## Tests

Run tests via command: php artisan test