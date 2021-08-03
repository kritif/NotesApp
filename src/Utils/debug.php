<?php

declare(strict_types=1);

error_reporting(E_ALL); //ustawia, by pokazywać każde błędy, nawet drobne Notice
ini_set('display_errors','1');// to i error_reporting zawsze razem ze sobą

function dump($data)
{
  echo '<br/><div 
    style="
      display: inline-block;
      padding: 3 5px;
      border: 1px solid black;
      background: lightgrey;
    "
  >
<pre>';
print_r($data);
echo '</pre>
</div>
<br/>';
}