<?php
/*
 * INPUT
 * a
 * b
 * c
 * 
 * VARIABLES = INPUT
 */

$this->a = $this->a ?? 'default a';
$this->b = $this->b ?? 'default b';
$this->c = $this->c ?? 'default c';
?>

<h1>Test component without class</h1>
<p>Some text here...</p>
<ul>
    <li>a: <?=$this->a?></li>
    <li>b: <?=$this->b?></li>
    <li>c: <?=$this->c?></li>
</ul>
