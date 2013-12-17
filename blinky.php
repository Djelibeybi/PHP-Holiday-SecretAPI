#!/usr/bin/php
<?php
/**
 * A blinky app based on blinky.py built upon the Secret API for Holiday by MooresCloud
 * A very simple app to create random colours and send them to the string
 *
 * @author Avi Miller <avi.miller@gmail.com>
 * @author Mark Pesce
 * @version 1.0
 * @copyright 2013 Avi Miller
 * @license MIT
 */

include 'holidaysecretapi.php';

// Need to pass the IP address or hostname of the Holiday as a command-line parameter
if ( isset($argv[1]) ) {
	$holiday = new HolidaySecretAPI($argv[1]);
	
	while (true) {
	
		$ln = 0;
		for ($ln = 0; $ln < $holiday->NUM_GLOBES; $ln++) {
			$r = rand(0, 63);
			$g = rand(0, 63);
			$b = rand(0, 63);
			
			// Now, based on another random value, squash one of these values
			switch (rand(0,2)) {
			
				case 0:
					$r = 0;
				break;
				
				case 1:
					$g = 0;
				break;
				
				case 2:
					$b = 0;
				break;
			}
			
			$holiday->setglobe($ln, $r, $g, $b);
			
		}
		
		$holiday->render();
		usleep(200000);
	
	}
	
} else {
	exit(1);
}

?>