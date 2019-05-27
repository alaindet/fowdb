<?php
// Class component
echo fd_test_component(
    "test/with-class",
    (object)[
        "a" => 10,
        "b" => 20,
        "c" => 30,
    ]
);
?>

<?php
// Dumb component
echo fd_test_component(
    "test/without-class",
    (object)[
        "a" => 10
    ]
);
?>

<h1>Test repeated component with class</h1>
<ul>
    <?php for ($i = 1; $i <= 2000; $i++) : ?>
        <?php
        // Repeated component
        echo fd_test_component(
            "test/multiple",
            (object)[
                "foo" => $i
            ]
        );
        ?>
    <?php endfor; ?>
</ul>
