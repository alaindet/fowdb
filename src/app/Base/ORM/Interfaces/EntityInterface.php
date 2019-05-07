<?php

namespace App\Base\ORM\Interfaces;

use App\Base\ORM\Entity\EntityCustomProperties;

interface EntityInterface
{
    public function props(): ?EntityCustomProperties;
}
