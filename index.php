<?php

require_once 'vendor/autoload.php';

use App\Shop;
use App\Flower;
use App\FlowerCollection;
use App\Suppliers\Courier;
use App\Suppliers\GardenShack;
use App\Suppliers\SQLSupply;
use App\Suppliers\Warehouse;


//IMPORT JSON FILE CONTENTS
$jsonFileContents = file_get_contents('storage/another-garden.json');

//IMPORT CSV FILE CONTENTS
$csvFile = file('storage/local-garden.csv');
$csvFileContents = [];
foreach ($csvFile as $item) {
    $csvFileContents[(string)explode(',', $item)[0]] = (int)explode(',', $item)[1];
}


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

//Add JSON and CSV contents to supplierTwo's inventory
foreach (json_decode($jsonFileContents, true) as $item) {
    $supplierTwo->addFlower($item['name'], $item['amount']);
}
foreach ($csvFileContents as $flower => $amount) {
    $supplierTwo->addFlower($flower, $amount);
}

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

//Add JSON and CSV contents to supplierTwo's inventory
foreach (json_decode($jsonFileContents, true) as $item) {
    $supplierTwo->addFlower($item['name'], $item['amount']);
}
foreach ($csvFileContents as $flower => $amount) {
    $supplierTwo->addFlower($flower, $amount);
}

$supplierFour = new SQLSupply();
$supplierFour->addFlowerArray([
    new Flower('tulip', 100),
    new Flower('rose', 140),
    new Flower('carnation', 3),
]);

// Add SQL contents to supplierFour's inventory
$supplierFour->addDataBaseFlowers();

function printShopAssortment(Shop $shop): void
{
    echo "<table>
            <tr>
                <th>Item</th>
                <th>Amount</th>
                <th>Price per pc. (€)</th>
            </tr>";

    foreach ($shop->getInventory()->getFlowers() as $item) {

        echo "<tr>
                <td>{$item->getName()}</td>
                <td>{$item->getAmount()}</td>";

        foreach ($shop->getPrices() as $flower => $price) {
            if ($flower === $item->getName()) {
                echo "<td>" . number_format($price / 100, 2) . "</td></tr>";
            }
        }
        echo PHP_EOL;
    }
    echo '</table>';
}

echo 'Shop inventory before shipment:<br>';
printShopAssortment($myShop);

$myShop->mergeShipmentWithInventory($supplierOne->shipment());
$myShop->mergeShipmentWithInventory($supplierTwo->shipment());
$myShop->mergeShipmentWithInventory($supplierThree->shipment());
$myShop->mergeShipmentWithInventory($supplierFour->shipment());

echo '<br><br>Shop inventory after shipment and merge:<br>';
printShopAssortment($myShop);

echo '<br>';

$gender = 'female';
$choiceFlower = 'tulip';
$choiceAmount = 2;

$myShop->removeFromInventory($choiceFlower, $choiceAmount);
$cost = $myShop->getPrices()[$choiceFlower];

echo "<br>You chose $choiceAmount {$choiceFlower}";
if ($choiceAmount > 1) {
    echo 's';
}

if ($gender === 'female') {
    echo '<br>BECAUSE YOU ARE A WOMAN YOU HAVE TO PAY A REDUCED PRICE OF €' . number_format(($cost - ($cost * 20 / 100)) / 100 * $choiceAmount, 2);
} else {
    echo '<br>YOU HAVE TO PAY €' . number_format($cost / 100 * $choiceAmount, 2);
}

echo '<br><br>Shop inventory after purchase:<br>';
printShopAssortment($myShop);

?>

<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>