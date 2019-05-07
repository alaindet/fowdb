<?php

namespace App\Base\Items\Interfaces;

interface ItemsCollectionDataReadInterface
{
    public function get(int $index);
    public function first();
    public function last();
}
