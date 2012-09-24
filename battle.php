<?php 

require_once('http.php');

class BattleNet
{

	private $profileUrl, $rankUrl;
	private $profilePage, $rankPage;
	private $wc;
	private $properties;
	
	private $profile_properties = array(
			 'nick' => 	'/<title>(.*?) - StarCraft/sim',
			,'season' => 	'/stat-block.*?>(\d+)<.*?stat-block.*?>\d+.\d+<.*?stat-block.*?>\d+<.*?stat-block.*?race">.*?</sim',
			,'mode' => 	'/stat-block.*?>\d+<.*?stat-block.*?>(\d+.\d+)<.*?stat-block.*?>\d+<.*?stat-block.*?race">.*?</sim',
			,'carrier' => 	'/stat-block.*?>\d+<.*?stat-block.*?>\d+.\d+<.*?stat-block.*?>(\d+)<.*?stat-block.*?race">.*?</sim',
			,'race' => 	'/stat-block.*?>\d+<.*?stat-block.*?>\d+.\d+<.*?stat-block.*?>\d+<.*?stat-block.*?race">(.*?)</sim',
			                        
			,'leagues' =>	'/"mode">(.*?)<.*?league-name.*?>(.*?)</sim'
		);
		
	private $league_properties = array(
			'position' => 	'/id="current-rank.*?>(\d+)-.*?<tr/sim',
			'score' => 	'/id="current-rank.*?center">(\d+).*?<tr/sim',
			'wins' => 	'/id="current-rank.*?center">\d+?<.*?center">(\d+)<.*?<tr/sim',
			'losses' => 	'/id="current-rank.*?center">\d+<(?:(?!<\/tr).)*center">\d+(?:(?!<\/tr).)*center">(\d+)/sim',
			'joined' => 	'/id="current-rank.*?>.*?(\d*.\d*.\d*)/sim',
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

}


?>