<?php
require_once('battle.php');

$profileUrl = "http://eu.battle.net/sc2/en/profile/543774/1/FXOLoWeLy/";
//$profileUrl = "http://eu.battle.net/sc2/en/profile/385468/1/PhAn/";

$b = new BattleNet( $profileUrl );

$leagues = Array();
foreach( $b->leagues as $key => $value ) {
	$leagues[] = $key.'-'.$value;
}

$properties = array( 
        'Nickname: ', 		$b->nick,			
	'Fav. Race:', 		$b->race,
	'Fav. Mode:', 		$b->mode,
	'Carrier games:', 	$b->carrier,
	'Season games:',	$b->season,	
	'Top leagues:',		implode(',', $leagues ),
	'Position:',		$b->position,
	'Score:',		$b->score,
	'Wins:',		$b->wins,
	'Losses:',		$b->losses,
	'Bonus pool:',		$b->bonuspool,
	'Joined:',		$b->joined,
	'JSON representation:',	$b->Json()
	);

              
for( $i = 0; $i < count($properties); $i+=2 ) {
	$data .= sprintf( "<dt>%s</dt>\n", $properties[$i] );
	$data .= sprintf( "<dd>%s</dt>\n", $properties[$i+1] );	
}


$html = <<< HTML

<html>
<head>
<title>Starcraft II - player stats</title>
<meta http-equiv="content-type" content="charset=utf-8"/>
<style> 
body {
	font-family: Arial;
	font-size: 11pt;
	background-color:Beige;
}
dd { 
	color:DarkSlateGray;
	margin: 0 0 15px 0;
}
dt {
	font-weight:bold;
	color:Brown;
}
</style>
</head>
<body>
<h3>Starcraft II Player Stats</h3>
<dl>
%s</dl>
</body>
</html>

HTML;

echo sprintf( $html, $data );

?>





