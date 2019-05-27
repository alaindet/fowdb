<?php
/*
 * INPUT
 * a
 * b
 * c
 * 
 * VARIABLES = INPUT
 */

$a = $this->a ?? 'default a';
$b = $this->b ?? 'default b';
$c = $this->c ?? 'default c';
?>

<h1>Test component without class</h1>
<p>Some text here...</p>
<ul>
    <li>a: <?=$a?></li>
    <li>b: <?=$b?></li>
    <li>c: <?=$c?></li>
</ul>
