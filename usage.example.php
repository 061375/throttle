<?php
// include and run the throttle before everything so (if necessary) it can kill everthing before
include('function.php');
throttle(10,'-2 minutes', 'Quite spamming my server!', '/path/to/.ips');

/**  
 * 
 * this is where you would bootstrap all your code for whatever your working on
 * 
 * require_once('/path-to/your_code/that/will/likely.takeup_a_lot_of.resources.php');
 * 
 * */ 
