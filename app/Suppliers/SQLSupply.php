<?php

namespace App\Suppliers;

use App\Flower;
use App\FlowerCollection;
use Medoo\Medoo;

class SQLSupply implements InventoryInterface
{
    private array $inventory = [];

    public function getFlowers(): array
    {
        return $this->inventory;
    }

    public function addFlower(Flower $flower): void
    {
        $this->inventory[] = $flower;
    }

    public function addFlowerArray(array $flowers): void
    {
        foreach ($flowers as $flower) {
            $this->addFlower($flower);
        }
    }

    public function addDataBaseFlowers()
    {
        $database = new Medoo([
            'database_type' => 'mysql',
            'database_name' => 'serious_supplier',
            'server' => 'localhost',
            'username' => 'root',
            'password' => 'aija'
        ]);

        $flowersData = $database->select('flowers', '*');

        foreach ($flowersData as $flowerData) {
            $this->addFlower(new Flower($flowerData['name'], (int)$flowerData['amount']));
        }

    }

    public function shipment(): FlowerCollection
    {
        $flowerCollection = new FlowerCollection();

        foreach ($this->inventory as $flower) {
            $flowerCollection->addFlower($flower);
        }

        return $flowerCollection;
    }
}