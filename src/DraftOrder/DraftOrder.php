<?php

namespace Pagewerx\UswerxApiPhp\DraftOrder;

use Exception;
use Pagewerx\UswerxApiPhp\Client\Client;
use Pagewerx\UswerxApiPhp\Context;
use Pagewerx\UswerxApiPhp\Contracts\LoggerInterface;

class DraftOrder
{
    private $uswerxId;
    public $DraftOrder;
    public $shopData;
    private $shopId;
    private $name;
    private Context $context;
    private LoggerInterface $logger;
    private Client $client;

    /**
     * DraftOrder constructor.
     *
     * @param array $data The data to be used to create the DraftOrder object.
     * @throws Exception
     */
    public function __construct() {}

    /**
     * @throws Exception
     */
    public static function create($data = []): DraftOrder
    {
        $draft = new self();
        $draft->client = new Client(); // Throws an exception if Context is not initialized.
        $draft->context = Context::getInstance();
        $draft->logger = $draft->context->getLogger();
        $draft->logger->debug('Creating DraftOrder object');
        $endpoint = $draft->client->getHost() . '/api/draft-orders';
        $headers = [
            'Authorization' => 'Bearer ' . $draft->client->getToken(),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        // set form data field line_items to the comma delimited list of SKUs
        if (!empty($data['line_items'])) {
            if (is_array($data['line_items'])) {
                $form_data['line_items'] = implode(',', $data['line_items']);
            } else {
                $form_data['line_items'] = $data['line_items'];
            }
        }

        //post $form_data to the endpoint
        $response = $draft->client->post($endpoint, [
            'headers' => $headers,
            'form_params' => $form_data ?? [],
        ]);
        $dataLog = "Response is a: ";
        if($response instanceof \GuzzleHttp\Psr7\Response) {
            $dataLog.= "Guzzle Response object";
        } else {
            $dataLog.= gettype($response);
        }
        $draft->logger->debug($dataLog);

        $responseObj = json_decode($response,false);
        $draft->logger->debug('DraftOrder created', [
            'api_response_string'   =>      $response,
            'api_response_object'   =>      $responseObj
        ]);
        $draft->DraftOrder = $responseObj;
        $draft->shopData = $draft->getDraftOrderData()->shop_data;
        $draft->shopId = $draft->shopData->id;
        $draft->name = $draft->shopData->name;
        $draft->uswerxId = $draft->DraftOrder->id;
        return $draft;
    }

    /*
    public function __construct(int $id, object $data)
    {
        $this->uswerxId = $id;
        $this->DraftOrder = $data;
        $this->shopData = $this->DraftOrder->shop_data;
        $this->shopId = $data->id;
        $this->name = $this->DraftOrder->shop_data->name;
    }
    */
    public function getShopId()
    {
        return $this->shopId;
    }

    public function getUswerxId()
    {
        return $this->uswerxId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Retrieves the raw draft order data.
     *
     * @return object|null The draft order data as an object.
     */
    public function getDraftOrderData(): ?object
    {
        return $this->DraftOrder;
    }

    public function getInvoiceUrl(): string
    {
        return $this->shopData->invoice_url;
    }

    public function getLineItems(): array
    {
        return $this->shopData->line_items;
    }
}