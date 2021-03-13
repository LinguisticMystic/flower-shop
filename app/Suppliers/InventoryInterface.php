<?php

namespace App\Suppliers;

use App\FlowerCollection;

interface InventoryInterface
{
    public function shipment(): FlowerCollection;
}