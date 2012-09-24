<?php 

require_once('http.php');

class BattleNet
{

	private $profileUrl, $rankUrl;
	private $profilePage, $rankPage;
	private $wc;
	private $properties;
	
	private $profile_properties = array(
			 'nick' => 	'/<title>(.*?)\s-\sStarCraft/sim'
			,'season' => 	'/Игр в текущем сезоне.*?>(\d+)?</sim'
			,'carrier' => 	'/Всего игр в карьере.*?>(\d+)?</sim'
			,'mode' => 	'/Любимый режим.*?>(\dх\d)?</sim'
			,'race' => 	'/Любимая раса.*?\"race\">(.*?)</sim'
			                        
			,'leagues' =>	'/"mode">(.*?)<.*?league-name.*?>(.*?)</sim'
		);

	private $league_properties = array(
			'position' => 	'/#current-rank.*?>(.*?)<.*?>(\d+)/sim'
		);


	public function __construct( $profileUrl) {
		$this->profileUrl = $profileUrl;
		$this->rankUrl = $profileUrl.'ladder/leagues#current-rank';
		
		$this->wc = new WebClient();

		$this->Refresh();
	}
		
	private function Refresh() {
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