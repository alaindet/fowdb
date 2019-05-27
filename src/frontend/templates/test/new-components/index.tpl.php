<?php
    $name = "test/with-class";
    $state = (object) [
        "a" => 10,
        "b" => 20,
        "c" => 30,
    ];
    echo fd_test_component($name, $state);
?>

<?=fd_test_component("test/without-class", (object) ["a" => 10])?>
