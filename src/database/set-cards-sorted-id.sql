SET @index := 0;
UPDATE
  cards
SET
  sorted_id = (SELECT @index := @index + 1)
ORDER BY
  clusters_id asc,sets_id ASC,
  num ASC,
  back_side ASC,
  narp ASC;
