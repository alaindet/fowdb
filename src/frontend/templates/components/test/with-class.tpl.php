<?php
/*
 * INPUT
 * a
 * b
 * c
 * 
 * VARIABLES
 * aa
 * bb
 */
?>
<h1>Test component with class</h1>

<h2>Available variables</h2>
<ul>
    <?php foreach ($this as $key => $value): ?>
        <li><?=$key?>: <?=$value?></li>
    <?php endforeach; ?>
</ul>
