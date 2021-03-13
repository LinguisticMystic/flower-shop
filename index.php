<?php

require_once 'vendor/autoload.php';

use App\Shop;
use App\Flower;
use App\FlowerCollection;
use App\Suppliers\Courier;
use App\Suppliers\GardenShack;
use App\Suppliers\Warehouse;

echo 'Hello World!<br><br>';

$myShop = new Shop('Feminist Flower Shop');

$myShop->createInventory($shopInventory = new FlowerCollection());
$shopInventory->addFlowerArray([
    new Flower('tulip', 20),
    new Flower('rose', 0),
    new Flower('carnation', 0)
]);

$myShop->setPrice('tulip', 80);
$myShop->setPrice('rose', 160);
$myShop->setPrice('carnation', 95);

$myShop->addSupplier($supplierOne = new Warehouse());
$myShop->addSupplier($supplierTwo = new GardenShack());
$myShop->addSupplier($supplierThree = new Courier());

$supplierOne->setFlowerCollection($firstBatch = new FlowerCollection());
$firstBatch->addFlowerArray([
    new Flower('tulip', 200),
    new Flower('rose', 140),
    new Flower('carnation', 260),
    new Flower('poisonous mushroom', 1)
]);

$supplierTwo->addFlower('rose', 100);
$supplierTwo->addFlower('tulip', 200);
$supplierTwo->addFlower('old sock', 1);

$supplierThree->takeFlowers('carnation', 20);

function printShopAssortment(Shop $shop): void
{
    foreach ($shop->getInventory()->getFlowers() as $item) {
        echo "Item: {$item->getName()} | Amount: {$item->getAmount()} | Price: €";
        foreach ($shop->getPrices() as $flower => $price) {
            if ($flower === $item->getName()) {
                echo number_format($price / 100, 2);
            }
        }
        echo PHP_EOL;
    }
}

echo 'Shop inventory before shipment:<br>';
printShopAssortment($myShop);

$myShop->mergeShipmentWithInventory($supplierOne->shipment());
$myShop->mergeShipmentWithInventory($supplierTwo->shipment());
$myShop->mergeShipmentWithInventory($supplierThree->shipment());

echo '<br><br>Shop inventory after shipment and merge:<br>';
printShopAssortment($myShop);

echo '<br>';

//$gender = readline('What is your gender (all genders welcome) ?...');
$gender = 'female';
//$choiceFlower = readline('Enter the name of the flower to buy...');
$choiceFlower = 'tulip';

//if (!in_array($choiceFlower, $myShop->getInventory()->getAllFlowerNames())) {
//    exit('No such item in shop');
//}

//do {
//    $choiceAmount = readline('Enter amount...');
//} while (!filter_var($choiceAmount, FILTER_VALIDATE_INT));

$choiceAmount = 2;

$myShop->removeFromInventory($choiceFlower, $choiceAmount);
$cost = $myShop->getPrices()[$choiceFlower];

echo "<br>You chose $choiceAmount {$choiceFlower}";
if ($choiceAmount > 1) {
    echo 's';
}

echo '<br>BECAUSE YOU ARE A WOMAN YOU HAVE TO PAY A REDUCED PRICE OF €';
if ($gender === 'female') {
    echo number_format(($cost - ($cost * 20 / 100)) / 100 * $choiceAmount, 2);
} else {
    echo number_format($cost / 100 * $choiceAmount, 2);
}

echo '<br><br>Shop inventory after purchase:<br>';
printShopAssortment($myShop);
