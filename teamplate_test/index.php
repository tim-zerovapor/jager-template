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

$jager->data['pagetitle'] = "Home | Jager Template Engine";


$jager->data['entries'] = array(
			array(
				'title'=>'Entry 1',
				'description' => "This is a fake description.1",
				'test'=> "this is a test "
				),
			array(
				'title'=>'Entry 2',
				'description' => "This is a fake description.2"
				),
			array(
				'title'=>'Entry 3',
				'description' => "This is a fake description.3"
				)
	);

$jager->data['downloads']= array(
				array(
					'title'=>'Super file 1 ',
					'description' => "This is a fake description.1",
					'download_text'=> "this is a test "
					),
				array(
					'title'=>'Super file 2',
					'description' => "This is a fake description.2",
					'download_text' => "Get it now!!!"
					),
				array(
					'title'=>'Super file 3',
					'description' => "This is a fake description.3",
					'download_text'=> 'download all the things'
					)
	);


echo  $jager->view("template.html");


// just some more time sutff 
$time_end = microtime_float();
$time = $time_end - $time_start;
echo "Executed in $time seconds and used ".convert(memory_get_usage(true));