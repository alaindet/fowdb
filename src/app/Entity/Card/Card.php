<?php

namespace App\Entity\Card;

use App\Base\ORM\Entity\Entity;

class Card extends Entity
{
    public $id;
    public $sorted_id;
    public $back_side;
    public $narp;
    public $clusters_id;
    public $sets_id;
    public $num;
    public $code;
    public $attribute_bit;
    public $type_bit;
    public $divinity;
    public $rarity;
    public $attribute_cost;
    public $free_cost;
    public $total_cost;
    public $atk;
    public $def;
    public $name;
    public $race;
    public $text;
    public $flavor_text;
    public $artist_name;
    public $image_path;
    public $thumb_path;
}
