<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Pagewerx\UswerxApiPhp\Context;
use Pagewerx\UswerxApiPhp\DraftOrder\DraftOrder;
use Pagewerx\UswerxApiPhp\Logging\DefaultLogger;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

class DraftOrderTest extends TestCase
{
    public $httpClient;
    public Context $context;
    public function setup(): void
    {
        $mock = new MockHandler([new Response(200, [], '{"site_id":1,"updated_at":"2024-01-31T23:21:18.000000Z","created_at":"2024-01-31T23:21:17.000000Z","id":21,"shopify_id":933994758301,"shop_data":{"id":933994758301,"note":null,"email":null,"taxes_included":false,"currency":"USD","invoice_sent_at":null,"created_at":"2024-01-31T18:21:17-05:00","updated_at":"2024-01-31T18:21:17-05:00","tax_exempt":false,"completed_at":null,"name":"#D20","status":"open","line_items":[{"id":57441280229533,"variant_id":null,"product_id":null,"title":"Estate Documents Pro: Comprehensive Estate Plan","variant_title":null,"sku":"EDP_1995","vendor":null,"quantity":1,"requires_shipping":false,"taxable":true,"gift_card":false,"fulfillment_service":"manual","grams":0,"tax_lines":[],"applied_discount":null,"name":"Estate Documents Pro: Comprehensive Estate Plan","properties":[],"custom":true,"price":"1995.00","admin_graphql_api_id":"gid://shopify/DraftOrderLineItem/57441280229533"},{"id":57441280262301,"variant_id":null,"product_id":null,"title":"Estate Documents Pro: Comprehensive Estate Plan","variant_title":null,"sku":"EDP_495","vendor":null,"quantity":1,"requires_shipping":false,"taxable":true,"gift_card":false,"fulfillment_service":"manual","grams":0,"tax_lines":[],"applied_discount":null,"name":"Estate Documents Pro: Comprehensive Estate Plan","properties":[],"custom":true,"price":"495.00","admin_graphql_api_id":"gid://shopify/DraftOrderLineItem/57441280262301"}],"shipping_address":null,"billing_address":null,"invoice_url":"https://sandboxpay.uswerx.com/63259771037/invoices/04e84c243d803a38a718ef55e0ca69eb","applied_discount":null,"order_id":null,"shipping_line":null,"tax_lines":[],"tags":"EDP","note_attributes":[],"total_price":"2490.00","subtotal_price":"2490.00","total_tax":"0.00","payment_terms":null,"admin_graphql_api_id":"gid://shopify/DraftOrder/933994758301"},"site":{"id":1,"name":"Estate Docs Pro","code":"EDP","active":1,"url":"https://estatedocspro.com","cc_descriptor":"SITE-CC-DESC-EDP","descriptor_ph":"555-555-1234","branding_url":"https://www.example.com/branding","disclaimer_url":"https://www.example.com/disclaimer","created_at":"2024-01-19T17:47:44.000000Z","updated_at":"2024-01-19T17:47:44.000000Z"}}')]);
        $handlerStack = HandlerStack::create($mock);
        $this->httpClient = new Client(['handler' => $handlerStack]);
        $context = Context::getInstance()->settings(
            'test_token',
            'https://example.com',
            new DefaultLogger(null, 'phpunit.log'),
            true,
            true,
            $this->httpClient
        );
    }
    public function testCreateDraftOrder(): DraftOrder
    {
        try {
            $draft = DraftOrder::new(['line_items' => ['EDP_1995','EDP_495']]);
            $this->assertSame(DraftOrder::class, get_class($draft));
            return $draft;
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    #[depends('testCreateDraftOrder')]
    public function testDraftOrderHasInvoiceURL(DraftOrder $draft)
    {
        $this->assertSame('https://sandboxpay.uswerx.com/63259771037/invoices/04e84c243d803a38a718ef55e0ca69eb', $draft->getInvoiceUrl());
    }

    #[depends('testCreateDraftOrder')]
    public function testDraftOrderHasUswerxId(DraftOrder $draft)
    {
        $this->assertIsInt($draft->getUswerxId());
    }

    #[depends('testCreateDraftOrder')]
    public function testDraftOrderHasShopifyId(DraftOrder $draft)
    {
        $this->assertIsInt($draft->getShopId());
    }

    #[depends('testCreateDraftOrder')]
    public function testDraftOrderHasName(DraftOrder $draft)
    {
        $this->assertIsString($draft->getName());
    }

    #[depends('testCreateDraftOrder')]
    public function testDraftOrderHasLineItems(DraftOrder $draft)
    {
        $draft = $this->testCreateDraftOrder();
        $this->assertIsArray($draft->getLineItems());
    }

    #[depends('testCreateDraftOrder')]
    public function testDraftOrderHasRawData(DraftOrder $draft) {
        $this->assertIsObject($draft->getDraftOrderData());
    }
}