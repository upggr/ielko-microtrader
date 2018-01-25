# GDAX PHP API Client Library
[![Latest Stable Version](https://poser.pugx.org/hellovoid/gdax/v/stable)](https://packagist.org/packages/hellovoid/gdax) [![Total Downloads](https://poser.pugx.org/hellovoid/gdax/downloads)](https://packagist.org/packages/hellovoid/gdax) [![License](https://poser.pugx.org/hellovoid/gdax/license)](https://packagist.org/packages/hellovoid/gdax)

This is the unofficial client library for the [GDAX API][1].
Inspired by [Coinbase PHP Library][2].

## Installation

Install the library using Composer. Please read the [Composer Documentation](https://getcomposer.org/doc/01-basic-usage.md) if you are unfamiliar with Composer or dependency managers in general.

```
composer require hellovoid/gdax
```
## Authentication

Use an API key, secret and passphrase to access your own GDAX account.

```php
use Hellovoid\Gdax\Configuration;
use Hellovoid\Gdax\Client;

$configuration = Configuration::apiKey($apiKey, $apiSecret, $apiPassphrase);
$client = Client::create($configuration);
```
## Response

Every successful method request returns decoded json array.

## Pagination [#ref](https://docs.gdax.com/#pagination)

Your requests should use these cursor values when making requests for pages after the initial request.

| Parameter  | Description |
| ------------- | ------------- |
| $before  | Request page before (newer) this pagination id. (default null)  |
| $after  | Request page after (older) this pagination id. (default null) |
| $limit  | Number of results per request. Maximum 100. (default 100) |

```php
use \Hellovoid\Gdax\Pagination;

$pagination = Pagination::create($before, null, $limit);

$client->setPagination($pagination);

$pagination->setEndingBefore(null);
$pagination->setStartingAfter($after);
```

## Accounts [#ref](https://docs.gdax.com/#accounts)
### List Accounts
```php
$client->getAccounts();
```
### Account details
```php
$client->getAccount($accountId);
```
### Account history
```php
$client->getAccountHistory($accountId);
```
### Account holds
```php
$client->getAccountHolds($accountId);
```
## Orders [#ref](https://docs.gdax.com/#orders)
### Place new order
```php
$order = $client->placeOrder([
    'size'       => 0.1,
    'price'      => 0.1,
    'side'       => 'buy',
    'product_id' => 'BTC-USD'
]);
```
### Cancel an order
```php
try {
    $response = $client->orderCancel($orderId);
} catch (HttpException $e) { // Order could not be canceled
    $e->getMessage();
}
```
### Cancel all orders
```php
$response = $client->ordersCancel();
```
Cancel all orders for a specific product:
```php
$response = $client->ordersCancel([
    'product_id' => $productId
]);
```
### List orders
```php
$response = $client->getOrders();
```
### Get order details
```php
$response = $client->getOrder($orderId);
```

## Fills [#ref](https://docs.gdax.com/#fills)
### List fills
```php
$response = $client->getFills([
    'order_id'   => 'all',
    'product_id' => 'all'
]);
```
## Funding [#ref](https://docs.gdax.com/#funding)
### List fundings
Get fundings with status "settled".
```php
$response = $client->getFundings([
    'status' => 'settled', // outstanding, settled, or rejected
]);
```
### Repay
```php
$response = $client->fundingRepay([
    'amount' => 1.00,
    'currency' => 'EUR',
]);
```

## Margin Transfer [#ref](https://docs.gdax.com/#margin-transfer)
```php
$response = $client->marginTransfer([
    'margin_profile_id' => '45fa9e3b-00ba-4631-b907-8a98cbdf21be',
    'type'              => 'deposit',
    'currency'          => 'USD',
    'amount'            => 2,
]);
```

## Position [#ref](https://docs.gdax.com/#position)
### Get overview of your profile
```php
$response = $client->position();
```
### Close
```php
$response = $client->positionClose([
    'repay_only' => true
]);
```

## Deposits [#ref](https://docs.gdax.com/#payment-method)
### Payment method
```php
$response = $client->depositPaymentMethod([
    'amount'            => 2.00,
    'currency'          => 'USD',
    'payment_method_id' => 'bc677162-d934-5f1a-968c-a496b1c1270b'
]);
```
### Coinbase
Deposit funds from a coinbase account.
```php
$response = $client->depositCoinbase([
    'amount'              => 2.00,
    'currency'            => 'BTC',
    'coinbase_account_id' => 'c13cd0fc-72ca-55e9-843b-b84ef628c198'
]);
```

## Withdrawals [#ref](https://docs.gdax.com/#payment-method53)
### Payment method
```php
$response = $client->withdrawalPaymentMethod([
    'amount'            => 2.00,
    'currency'          => 'USD',
    'payment_method_id' => 'bc677162-d934-5f1a-968c-a496b1c1270b'
]);
```
### Coinbase
Withdrawal funds to a coinbase account.
```php
$response = $client->withdrawalCoinbase([
    'amount'              => 2.00,
    'currency'            => 'BTC',
    'coinbase_account_id' => 'c13cd0fc-72ca-55e9-843b-b84ef628c198'
]);
```
### Crypto
Withdrawal funds to a crypto address.
```php
$response = $client->withdrawalCoinbase([
    'amount'         => 0.01,
    'currency'       => 'BTC',
    'crypto_address' => '0x5ad5769cd04681FeD900BCE3DDc877B50E83d469'
]);
```

## Payment methods [#ref](https://docs.gdax.com/#list-payment-methods)
Get a list of your payment methods.
```php
$response = $client->getPaymentMethods();
```

## Coinbase accounts [#ref](https://docs.gdax.com/#list-accounts59)
Get a list of your coinbase accounts.
```php
$response = $client->getCoinbaseAccounts();
```

## Reports [#ref](https://docs.gdax.com/#create-a-new-report)
### Create a new report
```php
$response = $client->createReport([
    'type' => 'fills',
    'start_date' => '2014-11-01T00:00:00.000Z',
    'end_date' => '2014-11-30T23:59:59.000Z'
]);
```
### Get report status
```php
$response = $client->getReportStatus($reportId);
```

## Products [#ref](https://docs.gdax.com/#products)
### Get products
```php
$response = $client->getProducts();
```
### Get Product Order Book
```php
$response = $client->getProductOrderBook($productId);
```
### Get Product Ticker
```php
$response = $client->getProductTicker($productId);
```
### Get Product Trades
```php
$response = $client->getProductTrades($productId);
```
### Get Historic Rates
```php
$response = $client->getProductHistoricRates($productId);
```
### Get 24hr Stats
```php
$response = $client->getProductLast24HrStats($productId);
```

## Currencies [#ref](https://docs.gdax.com/#currencies)
```php
$response = $client->getCurrencies();
```
### Get Time [#ref](https://docs.gdax.com/#time)
```php
$response = $client->getTime();
```
[1]: https://docs.gdax.com
[2]: https://github.com/coinbase/coinbase-php