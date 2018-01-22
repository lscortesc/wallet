# API REST Wallet Conekta

Service to create a wallet (Test)

## Requirements

- PHP >= 7.1.3
- Composer
- Mysql >= 5.7
- Curl >= 7

### How to use?

1. Clone repo
2. Run `composer install` in the project path
3. Create a database to wallet's project
4. Create `.env` file based on `.env.example`
5. Generate app key. Run `php artisan key:generate`
6. Config `.env` file. Follow vars are important:

| Environment var | Description |
| --------------- | ----------- |
| APP_URL         | URL's Project |
| DB_CONNECTION   | Connection Type (mysql default) |
| DB_HOST         | DB IP host |
| DB_PORT         | DB PORT (3306 default) |
| DB_DATABASE     | DB Name |
| DB_USERNAME     | DB Username |
| DB_PASSWORD     | DB Password |

7. Execute migrations. Run `php artisan migrate --seed`
8. Install passport. Run `php artisan passport:install`
9. Config passport client

| Environment var | Description |
| --------------- | ----------- |
| PASSWORD_CLIENT_ID | Type Client to Oauth Service (Always 2) |
| PASSWORD_CLIENT_SECRET | Secret generated in the step 8 |

### Endpoints

| Endpoint | Method | Body | Description |
| -------- | ------ | ---- | ----------- |
| /api/login | POST | email,password | Login - Retrun Token |
| /api/logout | POST | header -> Authorization Bearer $TOKEN |
| /api/login/refresh | POST | refresh_token | Return Token |
| /api/register | POST | name,email,password,password_confirmation | Return Customer register |
