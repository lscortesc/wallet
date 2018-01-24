# API REST Wallet Conekta

Service to create a wallet (Test)

You can visit on [heroku](https://larawallet.herokuapp.com)

## Requirements

- PHP >= 7.1.3
- Composer
- Mysql >= 5.6
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
| PASSWORD_CLIENT_ID | Password Client ID (step 8) |
| PASSWORD_CLIENT_SECRET | Password Secret (step 8) |

### Headers

```shell
Accept: application/json
Content-type: application/json
Authorization: Bearer $ACCESS_TOKEN (generated in /api/login endpoint - important to get resources that requires auth)
```

### Endpoints

| Endpoint | Method | Body | Description | Require Auth |
| -------- | ------ | ---- | ----------- | ------------ |
| /api/login | POST | email,password | Login - Retrun Token | No |
| /api/login/refresh | POST | refresh_token | Return Token | Yes |
| /api/register | POST | name,email,password,password_confirmation | Return Customer register | No |
| /api/wallet/balance | GET | - | Get My Wallet Balance | Yes |
| /api/wallet/balance/general | GET | - | Get balance of general account (use `email@test.com` and `secret` to login) | Yes |
| /api/wallet/fund | POST | amount,carnumber,exp_date(mm/YY),cvv | Fund your wallet | Yes |
| /api/wallet/transactions | GET | - | Get my transactions | Yes |
| /api/wallet/transfer/account | POST | amount,account_number,account_name,account_bank | Transfer to bank account | Yes |
| /api/wallet/transfer/{customer_id} | POST | amount | Transfer to another customer wallet | Yes |



