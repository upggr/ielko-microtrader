<?php

namespace Hellovoid\Gdax;

/**
 * A client for interacting with the GDAX API.
 */
class Client
{
    private $http;
    private $pagination;

    /**
     * Creates a new Coinbase client.
     *
     * @param Configuration $configuration
     * @param Pagination|null $pagination
     * @return Client
     */
    public static function create(Configuration $configuration, Pagination &$pagination = null)
    {
        return new static(
            $configuration->createHttpClient(),
            $pagination
        );
    }

    public function __construct(HttpClient $http, Pagination &$pagination = null)
    {
        $this->http = $http;
        $this->pagination = $pagination;
    }

    public function setPagination(Pagination &$pagination)
    {
        $this->pagination = $pagination;

        return $this;
    }

    public function getHttpClient()
    {
        return $this->http;
    }

    private function getPagination()
    {
        return $this->pagination;
    }

    public function decodeLastResponse()
    {
        if ($response = $this->http->getLastResponse()) {
            return $this->decodeResponse($response->getBody()->getContents());
        }
        return null;
    }

    public function decodeResponse($response)
    {
        return json_decode($response, true);
    }

    private function getAndDecodeData($path, array $params = [])
    {
        if ($response = $this->http->get($path, $params + ($this->getPagination() ?
                $this->getPagination()->prepareParams() : []))) {
            return $this->decodeResponse($response->getBody()->getContents());
        }
    }

    private function postAndDecodeData($path, array $params = [])
    {
        if ($response = $this->http->post($path, $params)) {
            return $this->decodeResponse($response->getBody()->getContents());
        }
    }

    private function deleteAndDecodeData($path, array $params = [])
    {
        if ($response = $this->http->delete($path, $params)) {
            return $this->decodeResponse($response->getBody()->getContents());
        }
    }

    public function getAccounts()
    {
        return $this->getAndDecodeData('accounts');
    }

    public function getAccount($accountId)
    {
        return $this->getAndDecodeData('accounts/'.$accountId);
    }

    public function getAccountHistory($accountId)
    {
        return $this->getAndDecodeData('accounts/'.$accountId.'/ledger');
    }

    public function getAccountHolds($accountId)
    {
        return $this->getAndDecodeData('accounts/'.$accountId.'/holds');
    }

    public function placeOrder(array $params = [])
    {
        return $this->postAndDecodeData('orders', $params);
    }

    public function getOrders(array $params = [])
    {
        return $this->getAndDecodeData('orders', $params);
    }

    public function getOrder($orderId)
    {
        return $this->getAndDecodeData('orders/'.$orderId);
    }

    public function orderCancel($orderId)
    {
        return $this->deleteAndDecodeData('orders/'.$orderId);
    }

    public function ordersCancel(array $params = [])
    {
        return $this->deleteAndDecodeData('orders', $params);
    }

    public function getFills(array $params = [])
    {
        return $this->getAndDecodeData('fills', $params);
    }

    public function getFundings(array $params = [])
    {
        return $this->getAndDecodeData('funding', $params);
    }

    public function fundingRepay(array $params = [])
    {
        return $this->postAndDecodeData('funding/repay', $params);
    }

    public function marginTransfer(array $params = [])
    {
        return $this->postAndDecodeData('profiles/margin-transfer', $params);
    }

    public function position()
    {
        return $this->getAndDecodeData('position');
    }

    public function positionClose(array $params = [])
    {
        return $this->postAndDecodeData('position/close', $params);
    }

    public function depositPaymentMethod(array $params = [])
    {
        return $this->postAndDecodeData('deposits/payment-method', $params);
    }

    public function depositCoinbase(array $params = [])
    {
        return $this->postAndDecodeData('deposits/coinbase-account', $params);
    }

    public function withdrawalPaymentMethod(array $params = [])
    {
        return $this->postAndDecodeData('withdrawals/payment-method', $params);
    }

    public function withdrawalCoinbase(array $params = [])
    {
        return $this->postAndDecodeData('withdrawals/coinbase-account', $params);
    }

    public function withdrawalTo(array $params = [])
    {
        return $this->postAndDecodeData('withdrawals/crypto', $params);
    }

    public function getPaymentMethods()
    {
        return $this->getAndDecodeData('payment-methods');
    }

    public function getCoinbaseAccounts()
    {
        return $this->getAndDecodeData('coinbase-accounts');
    }

    public function createReport(array $params = [])
    {
        return $this->postAndDecodeData('reports', $params);
    }

    public function getReportStatus($reportId)
    {
        return $this->getAndDecodeData('reports/'.$reportId);
    }

    public function getProducts()
    {
        return $this->getAndDecodeData('products');
    }

    public function getProductOrderBook($productId, array $params = [])
    {
        return $this->getAndDecodeData('products/'.$productId.'/book', $params);
    }

    public function getProductTicker($productId)
    {
        return $this->getAndDecodeData('products/'.$productId.'/ticker');
    }

    public function getProductTrades($productId)
    {
        return $this->getAndDecodeData('products/'.$productId.'/trades');
    }

    public function getProductHistoricRates($productId, array $params = [])
    {
        return $this->getAndDecodeData('products/'.$productId.'/candles', $params);
    }

    public function getProductLast24HrStats($productId)
    {
        return $this->getAndDecodeData('products/'.$productId.'/stats');
    }

    public function getCurrencies()
    {
        return $this->getAndDecodeData('currencies');
    }

    public function getTime()
    {
        return $this->getAndDecodeData('time');
    }
}