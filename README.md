# About
This project demonstrates implementation of customer charging using payment gateways - Shift4 and ACI using PHP 8, Symfony 6.4 and Docker.

# Usage

## Setting up
This project uses [symfony-docker](https://github.com/dunglas/symfony-docker) for configuring docker containers. Before running this project, make sure that docker along with compose is installed. After cloning the repository, go to the directory and copy the `.env.example` file to `.env`.
```bash
$ cp .env.example .env
```
Then, run provide the following commands
```bash
$ docker compose build --no-cache
$ docker compose up -d
```
The first command can be skipped if the image is already built and no customization is done.

Now, the project is up and running. Currently, there are 2 ways to perform the charging attempt. Using

1. API endpoint `/payment/{gateway}` or
2. Console command `bin/app-charge`

## Using API endpoint `/payment/{gateway}`:
Just perform a `POST` request to this endpoint using postman or any preferrable medium. The payment parameter accepts 2 values - `shift4` and `aci`. The request accepts JSON data.

### Request

|name|description|
|----|-----------|
|amount|The amount to charge, must be greater than 0|
|currency|Payment currency. Cupported values are - USD, EUR. Case sensitive|
|cardNumber|The 16 digit number that can be seen in the card|
|cardExpYear|Expiry year of the card. Can be found written on the card. Must be 4 digits|
|cardExpMonth|Expiry month of the card. Can be found written on the card along with expiry year. Must be 2 digits|
|cvv|The 3 digit value specified on the back side of the card|

### Response

|key|description|
|----|-----------|
|status|A short text defining status of the request. Values can be - ok, error|
|data|A JSON structure with some valid data sent only when status is 'ok'. The parameters are described in the following table|
|errors|Contains a list of errors. Only filled up when status is 'error'|

'data' JSON structure
|key|Description|
|---|-----------|
|transactionId|A unique transaction id. Currently these value is same as `id` passed from gateway response|
|dateOfCreation|A date time faormatted with millisecond and timezone offset defining the time of the transaction|
|amount|The amount that was charged|
|currency|Payment currency that was used in the transaction|
|cardBin|A 6 digit number that defines the bank with whom the card is associated|

### Example with success result
#### Request:
```
curl --insecure --location 'https://localhost/charge/aci' \
--header 'Content-Type: application/json' \
--data '{
    "amount": "1",
    "currency": "EUR",
    "cardNumber": "4200000000000000",
    "cardExpYear": "2031",
    "cardExpMonth": "05",
    "cvv": "123"
}'
```
Here, `--insecure` option has been used because the local SSL certificate is self-signed and cannot be verified.

#### Response
```
{
  "status": "ok",
  "data": {
    "transactionId": "8ac7a4a1917d2a3a0191888380917de1",
    "dateOfCreation": "2024-08-25 07:50:05.417+0000",
    "amount": "1.00",
    "currency": "EUR",
    "cardBin": "420000"
  },
  "errors": []
}
```

### Example with failed result
#### Request
```
curl --insecure --location 'https://localhost/charge/aci' \
--header 'Content-Type: application/json' \
--data '{
    "amount": "0",
    "currency": "EUR",
    "cardNumber": "4200000000000000",
    "cardExpYear": "2031",
    "cardExpMonth": "05",
    "cvv": "123"
}'
```
#### Response
```JSON
{
  "status": "error",
  "data": [],
  "errors": ["'amount' must be valid and greater than 0"]
}
```

## Using Console command `bin/app-charge`

To use from cli, provide the following command
```
docker exec -it api-network-call--sf64-php83-php-1 bin/console app:charge <gateway>
```
Please, note that, it is assumed that the directory name is same as git repository.

The `{gateway}` parameter can be either `shift4` or `aci` and it is case senitive. When running, the command will ask for the same parameters required in the API endpoint. After providing valid values, it will confirm the paramters and then perform the transaction and will show the result.

# Limitations
1. For Shift4, except for `amount` and `currency`, other arguments are ignored. To incorporate the card information, some other information will be required, and also 2 extra call to Shift4 will be needed (to get customer and card tokens). By deault a test card with number 4242424242424242 and my personal email for customer is being used.
2. For ACI, it is assumed that a VISA debit card is used for transaction in euro (EUR). Transaction using credit card has not yet been incorporated.
3. For both of the gateways, authorization parameters, base urls are hard-coded.

N.B.: If this project is continued, have plan to recover from the limitations.