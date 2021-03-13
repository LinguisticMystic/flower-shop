<?php

class Courier implements InventoryInterface
{
    private string $flowerType;
    private int $numberOfFlowers;

    public function takeFlowers(string $flower, int $amount)
    {
        $this->flowerType = $flower;
        $this->numberOfFlowers = $amount;
    }

    public function shipment(): FlowerCollection
    {
        $flowerCollection = new FlowerCollection();

        $flowerCollection->addFlower(new Flower($this->flowerType, $this->numberOfFlowers));

        return $flowerCollection;
    }
}