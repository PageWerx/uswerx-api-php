<?php
/*
 * In this example, we will create a new DraftOrder using the USWerx API PHP SDK.
 * Before creating a DraftOrder, you must have a Site created and an API Token for that Site.
 * At a minimum, you will need to set the Token and Host for the Site in the Context Object's settings.
 * The Context Object is a Singleton and must be initialized before using the SDK.
 */

// We need to require the Composer autoloader in our script, this pulls in dependencies like Guzzle and the USWerx API PHP SDK.
require_once __DIR__ . '/../vendor/autoload.php';

// We need to import the classes that we will be using in this script.
// This lets us use the DraftOrder class without needing to use the fully qualified namespace.
use Pagewerx\UswerxApiPhp\Context;
use Pagewerx\UswerxApiPhp\DraftOrder\DraftOrder;
use Pagewerx\UswerxApiPhp\Logging\DefaultLogger;

// We need to initialize the Context Singleton with the Token and Host for the Site. We will also use the DefaultLogger.
$context = Context::getInstance()->settings(
    '1|CDkdE6heDJizOwf9kp42uaajZAfbNOJS17YUsaQkf96dc9ca',
    'https://dev.uswerx.com',
    new DefaultLogger(),
    true,
    false,
    null
);

try {
    // We will create a new DraftOrder using the DraftOrder classes create method and pass in a couple SKUs.
    $draftOrder = DraftOrder::create(['line_items'=>['EDP_495', 'EDP_420']]);
    echo "Draft order \"{$draftOrder->getName()}\" created with Shopify ID: {$draftOrder->getShopId()}\n";

    // We will output the DraftOrder's Invoice URL to the console.
    echo "Draft Order Invoice URL: {$draftOrder->getInvoiceUrl()}\n";
} catch (Exception $e) {
    // If an exception is thrown, we will output the exception message to the console.
    echo "Exception: " . $e->getMessage() . "\n";
}