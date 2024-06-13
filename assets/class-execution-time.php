<?php
/**
 * Class to time the execution a method call...
 * Usage...
 * if(true === WP_DEBUG){
 * require_once(plugin_dir_path(__DIR__).'/assets/class-execution-time.php');
 * $executionTime = new Execution_Time('cf7_shortcode_request');
 * $executionTime->Start();
 * }
 * // code
 * if(true === WP_DEBUG){
 * $executionTime->end();
 * wpg_debug($executionTime->__toString());
 * }
 *
 * @package    Cf7_Grid_Layout
 * @subpackage Cf7_Grid_Layout/assets
 */

/**
 * Class Execution Time
 */
class Execution_Time {
	/**
	 * Start time
	 *
	 * @var Integer $start_time time in millisecond.
	 */
	private $start_time;

	/**
	 * End time
	 *
	 * @var Integer $end_time time in millisecond.
	 */
	private $end_time;
	/**
	 * Function to time
	 *
	 * @var String $fn fuction name.
	 */
	private $fn;
	/**
	 * Constructor
	 *
	 * @param String $fn_name function name to time.
	 */
	public function __construct( $fn_name ) {
		$this->fn = $fn_name;
	}
	/**
	 * Start timing
	 */
	public function start() {
		$this->start_time = getrusage();
	}
	/**
	 * End timing
	 */
	public function end() {
		$this->end_time = getrusage();
	}
	/**
	 * Get run time parameters
	 *
	 * @param Array  $ru array of data returned from resoruce usage function getrusage().
	 * @param Array  $rus array of data returned from resoruce usage function getrusage() at the start.
	 * @param String $index handle id for user|system data parameters, utime | stime.
	 */
	private function run_time( $ru, $rus, $index ) {
		return ( $ru[ "ru_$index.tv_sec" ] * 1000 + intval( $ru[ "ru_$index.tv_usec" ] / 1000 ) )
		- ( $rus[ "ru_$index.tv_sec" ] * 1000 + intval( $rus[ "ru_$index.tv_usec" ] / 1000 ) );
	}
	/**
	 * Method to printout the results of the timer.
	 */
	public function __toString() {
		return $this->fn . ' used ' . $this->runTime( $this->end_time, $this->start_time, 'utime' ) .
		" ms for its computations\nIt spent " . $this->runTime( $this->end_time, $this->start_time, 'stime' ) .
		" ms in system calls\n";
	}
}
