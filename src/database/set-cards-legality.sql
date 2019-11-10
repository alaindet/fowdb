/* 2019-11-10 08:58:21 */

UPDATE cards SET legality_bit = 0;

UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 1 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 0)
WHERE r.cards_id IS NULL AND c.clusters_id IN(1,2,3,4,5,6,7);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 2 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 1)
WHERE r.cards_id IS NULL AND c.clusters_id IN(2,3,4,5,6,7);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 3 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 2)
WHERE r.cards_id IS NULL AND c.clusters_id IN(5,6);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 4 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 3)
WHERE r.cards_id IS NULL AND c.clusters_id IN(1);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 5 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 4)
WHERE r.cards_id IS NULL AND c.clusters_id IN(2);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 6 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 5)
WHERE r.cards_id IS NULL AND c.clusters_id IN(3);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 7 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 6)
WHERE r.cards_id IS NULL AND c.clusters_id IN(4);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 8 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 7)
WHERE r.cards_id IS NULL AND c.clusters_id IN(5);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 9 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 8)
WHERE r.cards_id IS NULL AND c.clusters_id IN(6);


UPDATE cards c
LEFT JOIN (
    SELECT DISTINCT(cards_id)
    FROM play_restrictions
    WHERE formats_id = 10 AND deck = 0 AND copies = 0
) AS r ON c.id = r.cards_id
SET c.legality_bit = c.legality_bit | (1 << 9)
WHERE r.cards_id IS NULL AND c.clusters_id IN(7);


UPDATE cards AS base
INNER JOIN (
    SELECT name, max(legality_bit) AS legality_bit
    FROM cards
    WHERE narp = 2
    GROUP BY name
) AS `last`
ON base.name = `last`.name
SET base.legality_bit = `last`.legality_bit;

UPDATE cards AS memoriae
INNER JOIN (
    SELECT name, max(legality_bit) as legality_bit
    FROM cards
    WHERE name IN(SELECT DISTINCT name FROM cards WHERE narp = 4) AND narp <> 4
    GROUP BY name
) AS last_print_of_memorias_base_card
ON memoriae.name = last_print_of_memorias_base_card.name
SET memoriae.legality_bit = last_print_of_memorias_base_card.legality_bit;

