#!/usr/bin/php
<?php
/**
 * Holiday class implementation for the Secret API for Holiday by Moorescloud
 *
 * Homepage and documentation: http://dev.moorescloud.com
 *
 * @author Avi Miller <avi.miller@gmail.com>
 * @version 1.0
 * @copyright 2013 Avi Miller
 * @license MIT
 */

Class HolidaySecretAPI {

	// Holiday has 50 globes
	public $NUM_GLOBES = 50;
	
	// Internal array for the globes
	private $globes = array();
	
	// Internal connection details
	private $addr;
	private $port = 9988;
	private $sock;
	
	/** 
	 * If remote, we require the remote address. Currently, no checking of the address is
	 * performed
	 */	
	function __construct($addr) {
		if ( $addr == '') return false;
		
		// Initialise an array of globes set to all zeroes
		for ($globe = 0; $globe < $this->NUM_GLOBES; $globe++) {
			$this->globes[$globe] = array(0, 0, 0);
		}
		
		$this->addr = $addr;
		$this->sock = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP);
		
		//echo(print_r($this->globes, true));
				
	}//end function __construct()

	/**
	 * Set a globe
	 */
	public function setglobe($globenum, $r, $g, $b) {
		if ( $globenum < 0 || $globenum > $this->NUM_GLOBES) {
			return false;
		}
		
		if ( $r < 0 || $r > 63) $r = 0;
		if ( $g < 0 || $g > 63) $g = 0;
		if ( $b < 0 || $b > 63) $b = 0;
		
		$this->globes[$globenum] = array ((int)$r, (int)$g, (int)$b);
		
	}//end function setglobe()

	/**
	 * Sets the whole string to a particular colour
	 */
	public function fill($r, $g, $b) {
		
		foreach ($this->globes as $globenum => $globe_val) {
			$this->globes[$globenum][0] = (int)$r;
			$this->globes[$globenum][1] = (int)$g;
			$this->globes[$globenum][2] = (int)$b;
		}
		
	}//end function fill()

	/**
	 * Returns an array representing a globe's RGB colour value
	 */
	public function getglobe($globenum) {
		if ( $globenum < 0 || $globenum > $this->NUM_GLOBES) {
			return false;
		}
		
		return $this->globes[$globenum];
		
	}//end function getglobe()

	
	/**
	 * Rotate all of the globes around - up if TRUE, down if FALSE
	 */
	public function chase($direction = true) {
		return;
	}//end function chase()
	
	/**
	 * Rotate all of the globes up if TRUE, down if FALSE
	 * Set the new start of the string to the colour values
	 */
	public function rotate($newr, $newg, $newb, $direction = true) {
		return;
	}//end function rotate()


	/**
	 * The render function sends out a UDP packet using the SecretAPI
	 */
	public function render() {

		// First, pack the header
		$packet_data = pack("C*", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		
		// Next, pack each globe's RGB value
		foreach ($this->globes as $globe) {
			$packet_data .= pack("C*", $globe[0], $globe[1], $globe[2]);
		}//end foreach
		
		// Now, send the packet to the Holiday
		socket_sendto($this->sock, $packet_data, 160, 0, $this->addr, $this->port);

	}//end function render()
		
}//end class


// Basic testing if an IP address or hostname is passed on the command-line
if ( isset($argv[1]) ) {

	$holiday = new HolidaySecretAPI($argv[1]);
	
	while (true) {
		$r = rand(0, 255);
		$g = rand(0, 255);
		$b = rand(0, 255);
		$holiday->fill($r, $g, $b);
		$holiday->render();
		usleep(1000000);
	}

} else {
	exit(1);
}

?>