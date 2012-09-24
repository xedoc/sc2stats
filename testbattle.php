<meta http-equiv="content-type" content="charset=utf-8"/>

<?php
require_once('battle.php');

//$profileUrl = "http://eu.battle.net/sc2/ru/profile/543774/1/FXOLoWeLy/";

$profileUrl = "http://eu.battle.net/sc2/ru/profile/385468/1/PhAn/";

$b = new BattleNet( $profileUrl );

$leagues = Array();
foreach( $b->leagues as $key => $value ) {
	$leagues[] = $key.'-'.$value;
}


$template = "Ник: %s,<br> Раса: %s,<br> Режим: %s,<br> Карьерных игр: %s,<br> Сезонных игр: %s,<br> Лиги: %s,<br> Место: %s,<br> Очки: %s,<br> Победы: %s,<br> Поражения: %s,<br> Присоединился: %s<br>";
echo sprintf( $template, 
        $b->nick,
	$b->race,
	$b->mode,
	$b->carrier,
	$b->season,	
	implode(",", $leagues ),
	$b->position,
	$b->score,
	$b->wins,
	$b->losses,
	$b->joined	
	);
?>





