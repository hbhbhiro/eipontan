<?php
	require_once ("mathlib.php");
// p1 :file name   p2:item number (1-) // p3:request number of item 
	$p1 = $_GET["p1"];
	if ($p1 != "waru.csv" && $p1 !="waru1.csv") exit;
	$fname = $p1;
	$item =1;
	if ($p1=="waru1.csv") $item= 2;

	$p1 = $_GET["p2"];
	$parm = explode(",",$p1);
	$i=0;
	foreach( $parm as $x){
		$disp_on[$i]=intval($x);		///disp_onは1～
		$i++;
	}
	$nData=count($parm);
	$p1 = $_GET["p3"];
	$sel_item = intval($p1);			// sel_item 1～


       
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
			$ydata[$k][$j-1] = $val;			//yadata ０～
		}	
	
    	$border[$k] = $vdata[15][$k+1];
    	$border2[$k]= $vdata[16][$k+1];
	}
	   
 
    for ($k =0; $k<14;$k++){
    	if ($ydata[$sel_item-1][$k] == 0) break;
    }
//var_dump($ydata);
	$day = $k-1;
	
	if ($day == 13){
		$str = "<i> 対象のイベントは終了しました。結果は以下の通りでした。</i>";
		$str = $str."<br>★最終100位は ".round($ydata[$sel_item-1][$day]/10000)."万";
		$str = $str."<br>★背景ボーダーは ".round($border[$sel_item-1]/10000)."万";
		$str = $str."<br>★レアボーダーは ".round($border2[$sel_item-1]/10000)."万";
	} else {
	
	
//	print_r($day);echo " ";	
	$array = array();
	$array2 = array();
	foreach ($disp_on as $k) {	
		if ($k == $sel_item) continue;
//print_r($ydata[$i][$day]);
//echo ("  ");
		$array[$k] = $ydata[$k-1][13] * $ydata[$sel_item-1][$day] / $ydata[$k-1][$day];
		$array2[$k]= $border[$k-1] * $ydata[$sel_item-1][$day] / $ydata[$k-1][$day];
		
	}
	
	
	if (count($array) > 0 ){
	
		$max = max($array);
		$min = min($array);
		$ave = round(array_sum($array)/count($array));
	
		$max = round($max/10000);
		$min = round($min/10000);
		$ave = round($ave/10000);
//	var_dump($array);

		if ($max == $min){
			$str = "★最終100位は ".$ave."万";
		} else {
	  		$str = "★最終100位は ".$min."万～".$max."万  (平均値)".$ave."万";
		}
		
		$max = max($array2);
		$min = min($array2);
		$ave = round(array_sum($array2)/count($array2));
	
		$max_b = round($max/10000);
		$min_b = round($min/10000);
		$ave_b = round($ave/10000);
		
		if ($max_b == $min_b){
			$str = $str."<br>★背景ボーダーは ".$ave_b."万";
		} else {
	  		$str = $str."<br>★背景ボーダーは ".$min_b."万～".$max_b."万  (平均値)".$ave_b."万";
		}
		
			
	} else {
		$str = "比較データがありません";
	}
	}
	$str.="//";
		$str2 = "";
		$std_data =array (0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		$std_count = 0;
		foreach ($disp_on as $k) {	
			if ($k == $sel_item) continue;
//print_r($ydata[$i][$day]);
//echo ("  ");
			for ($i = 0 ; $i< 14; $i++){
				$std_data[$i] += $ydata[$k-1][$i];
			}
			$std_count++;	
		}
		if ($std_count > 0 ){
			$LR=new LinearRegression();			
			$week = array('月','火','水','木','金','土','日');
			$drate = array();
			$color_s = '<font color="#c0c0c0">';
			$color_e = '</font>';
			$xs=0;

			for ($i = 1;$i < 14;$i+=2){
				$ave = $std_data[$i] / $std_count;

				if ($ydata[$sel_item-1][$i] != 0 && $ave !=0 ){
					$delta =($ydata[$sel_item-1][$i] / $ave -1) * 100;

					//xとyのペアを繰り返して記録する
					$drate[$xs]=$delta;
					$xs++;
					$LR->addElement($xs,$delta);
				}
			}

			$lr_done = false;				
			$str3 = '';
			if (count($drate)>=3 && $day < 13){
				//水曜以降のみ
				$lr_done=$LR->computeLR();
				if($lr_done){
					$str3="<br>";
					$rate = $LR->getParamFactor() * 7 + $LR->getConstBase();
					$dev  = $LR->getParameterStexy() * 1.96;
					$sign = '';
					if (round($rate) >0 ) $sign = "+";
					$str3.= "○日曜対基準予測：".$sign . round($rate)."±". round($dev)."%";
				
//					$str3.="最終予想" . round($std_data[13]/$std_count*(1.0+($drate[$xs-1]/100)));

					$str3.="  最終100位予想";
					$str3.= round($std_data[13]/$std_count*(1.0+($rate-$dev)/100)/10000)."万～";
					$str3.= round($std_data[13]/$std_count*(1.0+($rate+$dev)/100)/10000)."万";
					$str3.="  (中心値)" . round($std_data[13]/$std_count*(1.0+$rate/100)/10000)."万";
	//				$str.="最終予想" . $drate[$xs-1];				
    //				$str.="<br>" . "y=".$LR->getConstBase()."+".$LR->getParamFactor()."x";
	//				$str.="<br>" . "y=".$LR->getConstBase1()."+".$LR->getParamFactor1()."x"."   ".$LR->getParameterCorrel()."  ".$LR->getParameterStexy();
				}else{
//    				echo "FAILED";
				}	
			}


			$k = 0;

			for ($i = 1;$i < 14;$i+=2){
				$cls='';
				$cle='';
				$sign = '';				
				if ($k < $xs ){
					$delta = $drate[$k];
					$delta = (int)round($delta);
					if ($delta > 0) $sign = '+';
					$k++;
				} else {
					if ($lr_done){
						$k++;
						$delta = $LR->getParamFactor() * $k + $LR->getConstBase();
						$delta = (int)round($delta);
						if ($delta > 0) $sign = '+';
						$cls=$color_s;
						$cle=$color_e; 
						
					} else {
						$delta = '＊';
					}
				}	
				if ($i != 1) $str2 .='/ ';
				$str2 = $str2.$week[$i/2].': '.$cls.$sign.$delta.$cle;
			}
						

			$str .= "<br>"."◎対基準（％）:".$str2.$str3;
										
	
		}
			
		
  mb_http_output ( 'UTF-8' );
  echo ($str);  

    
?>