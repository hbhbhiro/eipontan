<?php // content="text/plain; charset=utf-8"

if (isset($_POST['file'])){
	$fname = $_POST['file'];
	$s1_data= file ($fname);
	$ar_data = array();
  	$i = 0;
	foreach($s1_data as $x){
		$data = trim($x);
	    $vdata[$i] = explode(",",$data);
	    $i++;
	}
	echo json_encode($vdata);
}
?>