<?php

class GardenShack implements InventoryInterface
{
    private array $inventory = [];

    public function addFlower(string $flower, int $amount): void
    {
        $this->inventory[$flower] = $amount;
    }

    public function shipment(): FlowerCollection
    {
        $flowerCollection = new FlowerCollection();

        foreach ($this->inventory as $flower => $amount) {
            $flowerCollection->addFlower(new Flower($flower, $amount));
        }

        return $flowerCollection;
    }
}