<?php
//  not important just some time execution 
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = microtime_float();
function convert($size)
 {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
 }
// 
// 
// 
//  IMPORTANT STUFF HERE 


include("../jager_class/jager.php");

use Jager\Template\Engine as Engine;

$jager = new Engine;

$jager->data['pagetitle'] = "This is my page title woot";


echo  $jager->view("template.html");


// just some more time sutff 
$time_end = microtime_float();
$time = $time_end - $time_start;
echo "Executed in $time seconds and used ".convert(memory_get_usage(true));