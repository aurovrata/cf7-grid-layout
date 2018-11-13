<?php
/* Usage...
if(true === WP_DEBUG){
require_once(plugin_dir_path(__DIR__).'/assets/class-execution-time.php');
$executionTime = new ExecutionTime('cf7_shortcode_request');
$executionTime->Start();
}
//code
if(true === WP_DEBUG){
$executionTime->End();
debug_msg($executionTime->__toString());
}


*/
class ExecutionTime{
     private $startTime;
     private $endTime;
     private $fn;
     public function __construct($fn_name){
          $this->fn = $fn_name;
     }
     public function Start(){
         $this->startTime = getrusage();
     }

     public function End(){
         $this->endTime = getrusage();
     }

     private function runTime($ru, $rus, $index) {
         return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
     }

     public function __toString(){
         return $this->fn." used " . $this->runTime($this->endTime, $this->startTime, "utime") .
        " ms for its computations\nIt spent " . $this->runTime($this->endTime, $this->startTime, "stime") .
        " ms in system calls\n";
     }
 }
