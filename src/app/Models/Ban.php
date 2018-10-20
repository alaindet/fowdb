<?php

namespace App\Models;

use App\Models\BanStaticTrait;

class Ban
{
    use BanStaticTrait;

    /**
     * Maps the banned deck ID to its name
     *
     * @var array
     */
    public static $decks = [
        'Main Deck',
        'Side Deck',
        'Magic Stone Deck',
        'Rune Deck'
    ];

    /**
     * Flag to group results by card format
     *
     * @var boolean
     */
    private $groupByFormat = false;

    /**
     * Total count of banned cards
     *
     * @var integer
     */
    private $totalCount = 0;

    /**
     * Items fetched from the database
     *
     * @var array
     */
    private $items = [];

    /**
     * Activates the groupByFormat flag
     *
     * @return Ban
     */
    public function groupByFormat(): Ban
    {
        $this->groupByFormat = true;
        return $this;
    }

    /**
     * Total count accessor
     *
     * @return integer
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * Returns straight fetched items or
     * groups them by format if $this->groupByFormat is TRUE
     *
     * @return array
     */
    public function getItems(): array
    {
        // [
        //     [
        //         card_name=>,
        //         card_code=>,
        //         card_image=>,
        //         format_name=>
        //     ],
        //     ...
        // ]
        if (!$this->groupByFormat) return $this->items;

        // Group by format
        // Ex.: [ format => [ [name=>,code=>,image=>,format_code=>], .. ], .. ]
        $cache = '';
        return array_reduce(
            $this->items,
            function ($result, $item) use ($cache) {
                if ($cache !== $item['format_name']) {
                    $cache = $item['format_name'];
                }
                $result[$cache][] = [
                    'name' => $item['card_name'],
                    'code' => $item['card_code'],
                    'image' => $item['card_image'],
                    'format_code' => $item['format_code'],
                    'copies' => $item['ban_copies'],
                    'deck' => ($item['ban_deck'] > 0)
                        ? self::$decks[$item['ban_deck']]
                        : null
                ];
                return $result;
        }, []);
    }

    /**
     * Fetches data from the database
     *
     * @return array
     */
    public function fetch(): Ban
    {
        $this->items = database()->get(
            "SELECT
                cards.cardname as card_name,
                cards.code as card_code,
                cards.thumb_path as card_image,
                formats.name as format_name,
                formats.code as format_code,
                bans.deck as ban_deck,
                bans.copies as ban_copies
            FROM 
                bans
                INNER JOIN formats ON bans.formats_id = formats.id
                INNER JOIN cards ON bans.cards_id = cards.id
            ORDER BY
                bans.formats_id ASC,
                cards.cardname ASC,
                cards.sets_id DESC,
                cards.num ASC,
                cards.id ASC"
        );

        // Set total count (before grouping anyway)
        $this->totalCount = count($this->items);

        return $this;
    }
}
