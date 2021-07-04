/* 2019-11-11 01:45:35 */

UPDATE cards SET legality_bit = 0;

UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 0)
WHERE c.clusters_id IN(1,2,3,4,5,6,7);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 1)
WHERE c.clusters_id IN(2,3,4,5,6,7);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 2)
WHERE c.clusters_id IN(5,6);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 3)
WHERE c.clusters_id IN(1);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 4)
WHERE c.clusters_id IN(2);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 5)
WHERE c.clusters_id IN(3);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 6)
WHERE c.clusters_id IN(4);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 7)
WHERE c.clusters_id IN(5);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 8)
WHERE c.clusters_id IN(6);


UPDATE cards c
SET c.legality_bit = c.legality_bit | (1 << 9)
WHERE c.clusters_id IN(7);


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

