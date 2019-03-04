<?php

namespace App\Base\Items;

use App\Base\Items\ItemInterface;
use App\Base\Items\Exception\MissingPropertyNameException;
use App\Base\Items\Exception\InvalidPropertyNameException;
use App\Base\Items\Exception\MissingDataException;

abstract class Item implements ItemInterface {}
