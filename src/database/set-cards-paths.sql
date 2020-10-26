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
        WHEN c.layout = 1 THEN "j"
        WHEN c.layout = 2 THEN "sh"
        WHEN c.layout = 3 THEN "jj"
        WHEN c.layout = 4 THEN "in"
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
        WHEN c.layout = 1 THEN "j"
        WHEN c.layout = 2 THEN "sh"
        WHEN c.layout = 3 THEN "jj"
        WHEN c.layout = 4 THEN "in"
        ELSE ''
    END,
    '.jpg'
);
