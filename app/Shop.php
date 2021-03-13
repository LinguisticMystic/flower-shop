<?php

namespace App;

use App\Suppliers\InventoryInterface;

class Shop
{
    private string $name;
    private FlowerCollection $inventory;
    private array $prices = [];
    private array $suppliers = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function createInventory(FlowerCollection $collection): void
    {
        $this->inventory = $collection;
    }

    public function getInventory(): FlowerCollection
    {
        return $this->inventory;
    }

    public function setPrice(string $flower, int $price): void
    {
        $this->prices[$flower] = $price;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function addSupplier(InventoryInterface $supplier): void
    {
        $this->suppliers[] = $supplier;
    }

    public function mergeShipmentWithInventory(FlowerCollection $delivery): void
    {
        /** @var Flower $item */
        foreach ($this->inventory->getFlowers() as $item) {
            foreach ($delivery->getFlowers() as $flower) {
                if ($flower->getName() === $item->getName()) {
                    $item->incrementAmount($flower->getAmount());
                }
            }
        }
    }

    public function removeFromInventory(string $flowerName, int $amount)
    {
        for ($i = 0; $i < count($this->inventory->getFlowers()); $i++) {
            if ($flowerName === $this->inventory->getFlowers()[$i]->getName()) {
                $this->inventory->getFlowers()[$i]->decrementAmount($amount);
            }
        }
    }

}