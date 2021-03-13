<?php

class Warehouse implements InventoryInterface
{
    private FlowerCollection $flowerInventory;

    public function setFlowerCollection(FlowerCollection $collection): void
    {
        $this->flowerInventory = $collection;
    }

    public function shipment(): FlowerCollection
    {
        return $this->flowerInventory;
    }

}