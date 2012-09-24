<meta http-equiv="content-type" content="charset=utf-8"/>

<?php
require_once('battle.php');

$profileUrl = "http://eu.battle.net/sc2/ru/profile/543774/1/FXOLoWeLy/";

$b = new BattleNet( $profileUrl );

$leagues = Array();
foreach( $b->leagues as $key => $value ) {
	$leagues[] = $key.'-'.$value;
}


$template = "Ник: %s,<br> Раса: %s,<br> Режим: %s,<br> Карьерных игр: %s,<br> Сезонных игр: %s,<br> Лиги: %s<br>";
echo sprintf( $template, 
        $b->nick,
	$b->race,
	$b->mode,
	$b->carrier,
	$b->season,
	implode(",", $leagues ));
?>





