<?php

// Get database connection
$db = \App\Legacy\Database::getInstance();

$rulings = $db->get(
    "select
        created,
        is_errata,
        ruling,
        cardname,
        cardcode
    from rulings inner join cards
        on rulings.cards_id = cards.id
    order by created desc
    limit 10"
);

?>

<?php foreach ($rulings as $ruling): ?>
    <p>
        <u><?=$ruling["created"]?></u> - <strong><?=$ruling["cardname"]?></strong> (<?=$ruling["cardcode"]?>)<br>
        <?=$ruling["is_errata"] ? "ERRATA" : ""?>
        <?=$ruling["ruling"]?>
    </p>
    <hr>
<?php  endforeach; ?>
