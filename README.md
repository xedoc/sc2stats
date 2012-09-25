<code>
&lt;?php
require_once('battle.php');

$profileUrl = &quot;http://eu.battle.net/sc2/en/profile/543774/1/FXOLoWeLy/&quot;;
//$profileUrl = &quot;http://eu.battle.net/sc2/en/profile/385468/1/PhAn/&quot;;

$b = new BattleNet( $profileUrl );

$leagues = Array();
foreach( $b-&gt;leagues as $key =&gt; $value ) {
	$leagues[] = $key.'-'.$value;
}

$properties = array( 
        'Nickname: ', 		$b-&gt;nick,			
	'Fav. Race:', 		$b-&gt;race,
	'Fav. Mode:', 		$b-&gt;mode,
	'Carrier games:', 	$b-&gt;carrier,
	'Season games:',	$b-&gt;season,	
	'Top leagues:',		implode(',', $leagues ),
	'Position:',		$b-&gt;position,
	'Score:',		$b-&gt;score,
	'Wins:',		$b-&gt;wins,
	'Losses:',		$b-&gt;losses,
	'Bonus pool:',		$b-&gt;bonuspool,
	'Joined:',		$b-&gt;joined,
	'JSON representation:',	$b-&gt;Json()
	);

              
for( $i = 0; $i &lt; count($properties); $i+=2 ) {
	$data .= sprintf( &quot;&lt;dt&gt;%s&lt;/dt&gt;\n&quot;, $properties[$i] );
	$data .= sprintf( &quot;&lt;dd&gt;%s&lt;/dt&gt;\n&quot;, $properties[$i+1] );	
}


$html = &lt;&lt;&lt; HTML

&lt;html&gt;
&lt;head&gt;
&lt;title&gt;Starcraft II - player stats&lt;/title&gt;
&lt;meta http-equiv=&quot;content-type&quot; content=&quot;charset=utf-8&quot;/&gt;
&lt;style&gt; 
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
&lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
&lt;h3&gt;Starcraft II Player Stats&lt;/h3&gt;
&lt;dl&gt;
%s&lt;/dl&gt;
&lt;/body&gt;
&lt;/html&gt;

HTML;

echo sprintf( $html, $data );

?&gt;


</code>


