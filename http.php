<?php


class WebClient
{
	private $context;

	public function __construct() {
		$options = array( 'http' => 
			array( 'timeout' => 60 ) );
		$this->context = stream_context_create( $options );
	}
	public function get( $url ) {
		return file_get_contents( $url, false, $this->context );
	}	
}


?>