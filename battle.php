<?php 

require_once('http.php');

class BattleNet
{

	private $profileUrl, $rankUrl;
	private $profilePage, $rankPage;
	private $wc;
	private $properties;
	
	private $profile_properties = array(
			 'nick' => 	'/<title>(.*?) - StarCraft/sim'
			,'season' => 	'/stat-block.*?<h2>(\d+)<.*?<h2>.*?<.*?<h2>\d+<.*?race">.*?</sim'
			,'mode' => 	'/stat-block.*?<h2>\d+<.*?<h2>(.*?)<.*?<h2>\d+<.*?race">.*?</sim'
			,'carrier' => 	'/stat-block.*?<h2>\d+<.*?<h2>.*?<.*?<h2>(\d+)<.*?race">.*?</sim'
			,'race' => 	'/stat-block.*?<h2>\d+<.*?<h2>.*?<.*?<h2>\d+<.*?race">(.*?)</sim'
			                        
			,'leagues' =>	'/"mode">(.*?)<.*?league-name.*?>(.*?)</sim'
		);
		
	private $league_properties = array(
			'position' => 	'/id="current-rank.*?>(\d+).*?<tr/sim',
			'score' => 	'/id="current-rank.*?center">(\d+).*?<tr/sim',
			'wins' => 	'/id="current-rank.*?center">\d+?<.*?center">(\d+)<.*?<tr/sim',
			'losses' => 	'/id="current-rank.*?center">\d+<(?:(?!<\/tr).)*center">\d+(?:(?!<\/tr).)*center">(\d+)/sim',
			'joined' => 	'/id="current-rank.*?>.*?(\d+.\d+.\d+)/sim',
			'bonuspool' =>	'/bonus-pool".*?>(\d+)</sim'
		);


	public function __construct( $profileUrl) {
		$this->profileUrl = $profileUrl;
		$this->rankUrl = $profileUrl.'ladder/leagues#current-rank';
		
		$this->wc = new WebClient();

		$this->Refresh();
	}
		
	public function Refresh() {
		$this->profilePage = $this->wc->get( $this->profileUrl );
		$this->rankPage = $this->wc->get( $this->rankUrl );
		$this->properties = Array();
	}
	
	public function __get( $name )	{
		if( array_key_exists( $name, $this->properties ) ) {
			return $this->properties[$name];
		}
		foreach( array( array($this->profile_properties, $this->profilePage), 
			        array($this->league_properties, $this->rankPage)) as $arr ) {
			
			$props = $arr[0];
			if( array_key_exists( $name, $props ) ) {
				$content = $arr[1];
				$result = $this->readProperty( $props[$name], $content );
				$this->properties[$name] = $result;
				return $result;
			}
		}

	}
	private function readProperty( $regexp, $page )	{
		preg_match_all( $regexp, $page, $matches );
		if( count( $matches ) > 2 ) {
			$result = Array();
			for( $i = 0; $i < count($matches[1]); $i++ ) {
				$result[ $matches[1][$i] ] = trim($matches[2][$i]);
			}
		}
		else
		{
			$result = $matches[1][0];
		}		
		return $result;
	}
	public function Json(){
		$json = '{"nick":"%s","season":%s,"mode":"%s","carrier":%s,"race":"%s","leagues":[%s],"position":%s,"score":%s,wins:%s,"losses":%s,"joined":"%s","bonuspool":%s}';
		$leagues = Array();

		foreach( $this->leagues as $mode => $league ) {
			$item = sprintf( '["%s","%s"]', $league, $mode );
			$leagues[] = $item;
		}
		
		$losses = $this->losses;
		return sprintf( $json, 	$this->nick,
					$this->season,
					$this->mode,
					$this->carrier,
					$this->race,
					implode(',', $leagues),
					$this->position,
					$this->score,
					$this->wins,
					( empty($losses)?'0':$losses ),
					$this->joined,
					$this->bonuspool );
	}

}


?>