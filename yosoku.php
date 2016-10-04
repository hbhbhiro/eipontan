<?php
   
// p1 :file name   p2:item number (1-) // p3:request number of item 
	$p1 = $_GET["p1"];

	if ($p1 != "waru.csv" && $p1 !="waru1.csv") exit;
	$fname = $p1;
	$item =1;
	if ($p1=="waru1.csv") $item= 2;


	$p1 = $_GET["p2"];
	$sel_item = intval($p1)-1;
	
 	$p1 = $_GET["p3"];
	$item_num = intval($p1);

       
	$fp= fopen ($fname,"r");
	$data = fread ($fp,filesize($fname));
	fclose ($fp);

	$s1_data = explode("\n",$data);

	$ar_data = array();
	$i = 0;
	foreach($s1_data as $x){
		$csv_id = explode(",",$x);
		$vdata[$i]= $csv_id;
		$i++;
	}
	$y = $vdata[0];
	$nData = count($y) - 1;

	for ($k =0 ;$k<$nData;$k++){
		for ($j=1;$j<15;$j++){
			if ($vdata[$j][$k+1] =='-' ) $val = 0;
			else $val = intval($vdata[$j][$k+1]);
			$ydata[$k][$j-1] = $val;
		}	
	
    	$border[$k] = $vdata[15][$k+1];
    	$border2[$k]= $vdata[16][$k+1];
	}
	   
    for ($k =0; $k<14;$k++){
    	if ($ydata[$sel_item][$k] == 0) break;
    }
//var_dump($ydata);
	$day = $k-1;
//	print_r($day);echo " ";	
	$array = array();
	for ($i = 0; $i < $nData;$i++){
		if ($i == $sel_item) continue;
//print_r($ydata[$i][$day]);
//echo ("  ");
		$array[$i+1]= abs($ydata[$i][$day] - $ydata[$sel_item][$day]);
	}
//	var_dump($array);
	asort($array);
//	var_dump($array);
  $n = 0;
  $str ='';
  foreach ($array as $key => $value){
//  print $key.'=>'.$value .' ';
	  if ($n>0) $str.=',';
	  $str.=$key;
	  $n+=1;
	  if ($n == $item_num) break;
  }
  mb_http_output ( 'UTF-8' );
  echo ($str);  

    
?>