<?php

// Get database connection
$db = \App\Legacy\Database::getInstance();

$rulings = $db->get(
    "select
        created,
        is_errata,
        ruling,
        name,
        code
    from rulings inner join cards
        on rulings.cards_id = cards.id
    order by created desc
    limit 10"
);

?>

<?php foreach ($rulings as $ruling): ?>
    <p>
        <u><?=$ruling["created"]?></u> - <strong><?=$ruling["name"]?></strong> (<?=$ruling["code"]?>)<br>
        <?=$ruling["is_errata"] ? "ERRATA" : ""?>
        <?=$ruling["ruling"]?>
    </p>
    <hr>
<?php  endforeach; ?>
