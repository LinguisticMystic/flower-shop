<?php

namespace App;

class FlowerCollection
{
    private array $flowers = [];

    public function getFlowers(): array
    {
        return $this->flowers;
    }

    public function addFlower(Flower $flower): void
    {
        $this->flowers[] = $flower;
    }

    public function addFlowerArray(array $flowers): void
    {
        foreach ($flowers as $flower) {
            $this->addFlower($flower);
        }
    }

    public function getAllFlowerNames(): array
    {
        $flowerNames = [];
        foreach ($this->flowers as $flower) {
            $flowerNames[] = $flower->getName();
        }
        return $flowerNames;
    }

}