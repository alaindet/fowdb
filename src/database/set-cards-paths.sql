/* 2019-11-11 01:45:05 */

UPDATE cards AS c
INNER JOIN game_sets AS s ON c.sets_id = s.id
SET
c.image_path = CONCAT(
    'images/cards',
    '/', c.clusters_id,
    '/', s.code,
    '/', LPAD(c.num, 3, '0'),
    CASE
        WHEN c.back_side = 1 THEN "j"
        WHEN c.back_side = 2 THEN "sh"
        WHEN c.back_side = 3 THEN "jj"
        WHEN c.back_side = 4 THEN "in"
        ELSE ''
    END,
    '.jpg'
),
c.thumb_path = CONCAT(
    'images/thumbs',
    '/', c.clusters_id,
    '/', s.code,
    '/', LPAD(c.num, 3, '0'),
    CASE
        WHEN c.back_side = 1 THEN "j"
        WHEN c.back_side = 2 THEN "sh"
        WHEN c.back_side = 3 THEN "jj"
        WHEN c.back_side = 4 THEN "in"
        ELSE ''
    END,
    '.jpg'
);