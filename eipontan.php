<!DOCTYPE html>
<html lang="ja">
<head>
<!--<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, maximum-scale=10.0, user-scalable=yes">　-->
<meta name="viewport" content="target-densitydpi=device-dpi, width=860px, maximum-scale=10.0, user-scalable=yes">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex">
	<title>えいぽんたん</title>
	<link rel="stylesheet" type="text/css" href="style.css">

<script type="text/javascript" src="/lib/jquery-3.1.1.min.js"></script>
<!-- 暫定Ajaxライブラリを読み込む -->

	<script type    = "text/javascript" 
	charset = "utf-8"
	src     = "jslb_ajax.js"></script>
        
<!--<script type="text/javascript" src="canvasjs.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script> 
<script type="text/javascript" src="mylib.js"></script> 
<script type="text/javascript" src="eipontan.js"></script> 


   
   <script type="text/javascript">
function ChangeTab(tabname) {
	  if (tabname=="tab1") currentSelTab = 1;
      if (tabname=="tab2") currentSelTab = 2;
      if (tabname=="tab3") currentSelTab = 3;    

   // 全部消す
   document.getElementById('tab1').style.display = 'none';
   document.getElementById('tab2').style.display = 'none';
   document.getElementById('tab3').style.display = 'none';
   // 指定箇所のみ表示
   document.getElementById(tabname).style.display = 'block';
      document.getElementById('tabA').style.backgroundColor='lightcyan';
      document.getElementById('tabB').style.backgroundColor='lightcyan';
      document.getElementById('tabC').style.backgroundColor='lightcyan';
      document.getElementById('tabA').style.color='black';
      document.getElementById('tabB').style.color='black';
      document.getElementById('tabC').style.color='black';
      if (tabname=="tab1") document.getElementById('tabA').style.backgroundColor = 'blue';  
      if (tabname=="tab2") document.getElementById('tabB').style.backgroundColor = 'blue';  
      if (tabname=="tab3") document.getElementById('tabC').style.backgroundColor = 'blue';  
      if (tabname=="tab1") document.getElementById('tabA').style.color = 'white';  
      if (tabname=="tab2") document.getElementById('tabB').style.color = 'white';  
      if (tabname=="tab3") document.getElementById('tabC').style.color = 'white';
    if (firstDisplay == false){		//初回だけは表示し直す必要はない（デフォルトノーマルモードの場合）
		if (tabname=="tab1") graph_select(1);
   	  	if (tabname=="tab2") graph_select(2);
   	 } 	
	firstDisplay = false;
  
}
</script>

<script type="text/javascript">
  var ua, isIE, array, version;
 
// UserAgetn を小文字に正規化
  ua = window.navigator.userAgent.toLowerCase();
 
// IE かどうか判定
  isIE = (ua.indexOf('msie') >= 0 || ua.indexOf('trident') >= 0);
  
//  if (isIE) alert("IE");
 
</script>
   
   
<script type="text/javascript">

	data_load();
   // ▼上記のグラフを描画するための記述
	window.onload = function () {
		 setInterval("latestDisplay()",700);
  		graph_init();
  		if (currentSelTab == 1) {
  			ChangeTab('tab1');
  		}
  		if (currentSelTab == 2)	{
  			ChangeTab('tab2');
  		}	
  		document.querySelector("div.tabbox").style.display = "block";
 
	}


</script>

</head>


<body>

<?php

//IE11を含むIE判定
$isIE = 0;
$ua = $_SERVER['HTTP_USER_AGENT'];
if (strstr($ua, 'Trident') || strstr($ua, 'MSIE')) {
    $isIE = 1;
}

   define('EVENT_XMAS', 20);
   $dispBorder = 0;
   $dispBorder2 = 0;

  $nData = 5;

  $disp_on = array(1,2,3,4,5,6,7,8,9);

   $fname = "waru.csv";
   $fp= fopen ($fname,"r");
   $data = fread ($fp,filesize($fname));
   fclose ($fp);

  $s1_data = explode("\n",$data);

  $i = 0;
  foreach($s1_data as $x){
    $csv_id = explode(",",$x);
    $vdata[$i]= $csv_id;
    $i++;
  }
//     print_r ($vdata);
//    echo "<hr>";
       
    $y=$vdata[0];
//     print_r ($y);    echo "<hr>";
  $nData = count($y)-1;


//  $k=0;
//  for ($k =0 ;$k<$nData;$k++){
//    for ($j=1;$j<15;$j++){
//      $ydata[$k][$j-1] = $vdata[$j][$disp_on[$k]];
//    }
//  }


//  print_r ($ydata[0]);
//      echo "<hr>";

   $fname2 = "waru1.csv";
   $fp= fopen ($fname2,"r");
   $data = fread ($fp,filesize($fname2));
   fclose ($fp);

  $s1_data = explode("\n",$data);

  $i = 0;
  foreach($s1_data as $x){
    $csv_id = explode(",",$x);
    $vdata2[$i]= $csv_id;
    $i++;
  }
//     print_r ($vdata);
//    echo "<hr>";
       
    $y=$vdata2[0];
//     print_r ($y);    echo "<hr>";
  $nData2 = count($y)-1;

?>


<!--    ================================ -->



<div class="test" align="right">

	<label for="graphmode" >
	<input type="checkbox" id="graphmode" value="1" onclick="change_mode();"><i>インタラクティブモード&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i></label>

	<a href="eventlist.php">イベント一覧</a>
	</div><hr>

<h1 style="color :#2020f0;background-color:#ffe0f0;display:none">ワルぽん背景ポイント/ねこさま背景獲得推移</h1>

 <script type="text/javascript">
 		$(function(){
  			$("h1").fadeIn(2000);
  		});
</script>  		


<div class="tabbox" >
   <p class="tabs" >
	
      <a href="#tab1" class="tabz" id="tabA" onclick="ChangeTab('tab1'); return false;">ワルぽん (400位) </a>
      <a href="#tab2" class="tabz" id="tabB" onclick="ChangeTab('tab2'); return false;">ワルぽん (250位) </a>
      <a href="#tab3" class="tabz" id="tabC" onclick="ChangeTab('tab3'); return false;">ねこさま</a>
   
   </p>

   
   <div id="tab1" class="tab">
   
   
<?php
  $dispData="";
  for( $i=0; $i<$nData; $i++ ){
    if( $dispData != "" ) {
      $dispData.=",";
    }
    $dispData.=strval($i+1);
 
  } 
?>
 
<img id="graphContainer" src="graph.php?p1=<?= $dispData ?>&p2=<?= $fname ?>&p3=<?= $dispBorder ?>&p4=<?= $dispBorder2 ?>" alt="graph">
<!--<img id="graphContainer"  >-->

<div id="chartContainer" style="height: 600px; width: 800px; border-style: solid ;display:none;">  </div>
<br>
<b style="color :#f00000">●グラフ表示したいイベント名を選択して、<span style="background-color: #f0fff0">グラフ表示更新 </span>ボタンを押してください</b><br>

<input type="button" value="　　グラフ表示更新　　" onclick="boxCheck(1)">
<input type="button" value="全て選択" onclick="chBxOn(1,true);boxCheck(1 );">
<input type="button" value="全て解除" onclick="chBxOn(1,false)">
<label><b style="color :#0080ff"><input type="checkbox" id ="chkborder1" value="1" onclick="itemCheck(1);">背景ボーダーを表示</b></label>
<label><b style="color :#008080"><input type="checkbox" id ="chkborder1b" value="1" onclick="itemCheck(1);">レアボーダーを表示</b></label>

<form name="fchkbox" class="formstyle">
<ul>
<li>
<p class="check" style="color :#000000">

<?php
//  echo $nData;
  for ($i=0;$i<$nData;$i++){
?>

<label class="lchkbox"><input type="checkbox" name ="chkbox" value="<?= $i+1 ?>" checked="checked" onclick="itemCheck(1);"><?= $vdata[0][$i+1] ?></label>

<label class="mark1" style="color :#ffffff">&nbsp;&nbsp;</label>
<label class="mark1" style="color :#ffffff">&nbsp;&nbsp;</label>
<label class="mark1" style="color :#ffffff">&nbsp;&nbsp;</label>


<?php
  }
?>
<p>


 


</li>
</ul>
</form>

<script>
 ofs =0;
</script> 
<?php
  $mark = 0;
  for ($k=0;$k<$nData;$k++){

?>  
<script>
  mark = "<?= $vdata[17][$k+1]; ?>";
  doc=document.getElementsByClassName("mark1");
  index = 0;
  if (mark.indexOf('R') != -1) {doc[ofs+index].style.backgroundColor = "#1010ff";doc[ofs+index].innerHTML="ﾚｱ";index++;}
  if (mark.indexOf('N') != -1) {doc[ofs+index].style.backgroundColor = "#ee00ff";doc[ofs+index].innerHTML="ﾈｺ";index++;}
  if (mark.indexOf('C') != -1) {doc[ofs+index].style.backgroundColor = "#ff0000";doc[ofs+index].innerHTML="ﾁﾘ";index++;}
  ofs=ofs+3;
</script>

<?php } ?>

<hr color="emonchiffon">
&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;<font color="#ffffff" style="background-color:#ee00ff">ﾈｺ</font>ねこさま明け
&nbsp;<font color="#ffffff" style="background-color:#1010ff">ﾚｱ</font>水曜レア
&nbsp;<font color="#ffffff" style="background-color:#ff0000">ﾁﾘ</font>チリボーナス

<hr>
<b><i>対象イベント
<!--- <i><font color="#0020f0" style="background-color:#ffeed0"><?= $vdata[0][$nData]?></font></i> -->
</i></b>

<select name="event_name" id= "target_event" style="background-color:#ffeed0; font-weight: bold;">
<?php
	for ($m = $nData; $m >0; $m--){
?>		
<option value="<?= $m ?>"><?= $vdata[0][$m]?></option>
<?php } ?>
</select>


<label class="radio"><input type="radio" name ="yradio" value="1" checked="checked" >1つ</label>
<label class="radio"><input type="radio" name ="yradio" value="2" >2つ</label>
<label class="radio"><input type="radio" name ="yradio" value="3" >3つ</label>
<label class="radio"><input type="radio" name ="yradio" value="4" >4つ</label>
<label class="radio"><input type="radio" name ="yradio" value="5" >5つ</label>

<input type="button" value= "近いイベントにチェック"  onclick = "yosoku_sel(1);" title="対象イベントの最新のポイントに近いイベントに&#13;左で選択した数だけチェックを入れます">
<br>
<input type="button" value= "チェックしたイベント基準にボーダーを予測"  onclick = "yosoku_cal(1)">
<br>
<b id="yosoku_result_a" style="color :coral"> &nbsp;</b>
<b id="yosoku_result_b" style="color :darkred"> &nbsp;</b>
<hr>


</div>


  <div id="tab2" class="tab">
   
   
<?php
  $dispData2="";
  for( $i=0; $i<$nData2; $i++ ){
     if ($i ==EVENT_XMAS) continue;
    if( $dispData2 != "" ) {
      $dispData2.=",";
    }
    
    $dispData2.=strval($i+1);
 
  } 
?>

<img id="graphContainer1" src="graph.php?p1=<?= $dispData2 ?>&p2=<?= $fname2 ?>&p3=<?= $dispBorder ?>&p4=<?= $dispBorder2 ?>" alt="graph" >

<!--<img id="graphContainer1" >-->

<div id="chartContainer1" style="height: 600px; width: 800px; border-style: solid ;display:none;">  </div>
<br>

<b style="color :#f00000">●グラフ表示したいイベント名を選択して、<span style="background-color: #f0fff0">グラフ表示更新 </span>ボタンを押してください</b><br>
<input type="button" value="　　グラフ表示更新　　" onclick="boxCheck(2)" >
<input type="button" value="全て選択" onclick="chBxOn(2,true);boxCheck(2 );">
<input type="button" value="全て解除" onclick="chBxOn(2,false)">
<label><b style="color :#0080ff"><input type="checkbox" id ="chkborder2" value="1" onclick="itemCheck(2);">背景ボーダーを表示</b></label>
<label><b style="color :#008080"><input type="checkbox" id ="chkborder2b" value="1" onclick="itemCheck(2);">レアボーダーを表示</b></label>

<form name="fchkbox2" class="formstyle">
<ul>
<li>
<p class="check" style="color :#000000">
<?php
//  echo $nData;
  for ($i=0;$i<$nData2;$i++){
?>

<label class="lchkbox2"><input type="checkbox" name ="chkbox2"  value="<?= $i+1 ?>" checked="checked" onclick="itemCheck(2);"><?= $vdata2[0][$i+1] ?></label>
<label class="mark2" style="color :#ffffff">&nbsp;&nbsp;</label>
<label class="mark2" style="color :#ffffff">&nbsp;&nbsp;</label>
<label class="mark2" style="color :#ffffff">&nbsp;&nbsp;</label>

<?php if ((($i+1)%3)==0):?>
</p></li><li><p class="check" style="color :#000000">
<?php endif; ?>

<?php
  }
?>
<p>
</li>
</ul>

</form>


<script type="text/javascript">
  // クリスマスは外す
  document.fchkbox2.elements[20].checked = false;
</script>   

<script>
 ofs =0;
</script> 
<?php
  $mark = 0;
  for ($k=0;$k<$nData2;$k++){

?>  
<script>
  mark = "<?= $vdata2[17][$k+1]; ?>";
  doc=document.getElementsByClassName("mark2");
  index = 0;
  if (mark.indexOf('R') != -1) {doc[ofs+index].style.backgroundColor = "#1010ff";doc[ofs+index].innerHTML="ﾚｱ";index++;}
  if (mark.indexOf('N') != -1) {doc[ofs+index].style.backgroundColor = "#ee00ff";doc[ofs+index].innerHTML="ﾈｺ";index++;}
  if (mark.indexOf('C') != -1) {doc[ofs+index].style.backgroundColor = "#ff0000";doc[ofs+index].innerHTML="ﾁﾘ";index++;}
  ofs=ofs+3;
</script>

<?php } ?>

<hr color="emonchiffon">
&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;<font color="#ffffff" style="background-color:#ee00ff">ﾈｺ</font>ねこさま明け
&nbsp;<font color="#ffffff" style="background-color:#1010ff">ﾚｱ</font>水曜レア
&nbsp;<font color="#ffffff" style="background-color:#ff0000">ﾁﾘ</font>チリボーナス

<hr>

<b><i>対象イベント
<!--- <i><font color="#0020f0" style="background-color:#ffeed0"><?= $vdata2[0][$nData2]?></font></i>-->
</i></b>

<select name="event_name" id= "target_event1" style="background-color:#ffeed0; font-weight: bold;">
<?php
	for ($m = $nData2; $m >0; $m--){
?>
			
<option value="<?= $m ?>"><?= $vdata2[0][$m]?></option>

<?php } ?>
</select>

<label class="radio"><input type="radio" name ="yradio2" value="1" checked="checked" >1つ</label>
<label class="radio"><input type="radio" name ="yradio2" value="2" >2つ</label>
<label class="radio"><input type="radio" name ="yradio2" value="3" >3つ</label>
<label class="radio"><input type="radio" name ="yradio2" value="4" >4つ</label>
<label class="radio"><input type="radio" name ="yradio2" value="5" >5つ</label>

<input type="button" value= "近いイベントにチェック" onclick= "yosoku_sel(2);" title="対象イベントの最新のポイントに近いイベントに&#13;左で選択した数だけチェックを入れます">
<br>
<input type="button" value= "チェックしたイベント基準にボーダーを予測" onclick= "yosoku_cal(2)">
<br>
<b id="yosoku_result2_a" style="color :coral"> &nbsp;</b>
<b id="yosoku_result2_b" style="color :darkred"> &nbsp;</b>
<hr>


</div>


   <div id="tab3" class="tab">
   <img src="neko.php" id = "graph3" alt="graph"><br>
   
   <b style="color :#000000">■背景献上数</b>   
   <b style="color :#1e90ff">■15000</b>
   <b style="color :#66cdaa">■16000</b>
   <b style="color :#eeb422">■17000</b>
   <b style="color :#b22222">■18500</b>
  
</div>
</div>

<!--  ==========================================================================================================   -->

<script>

function boxCheck(item ){


  //チェックされた項目を記録する変数
  var str="";
  var border = "0";
  var border2 = "0";
  if (item == 1) {
	 obj = document.fchkbox;
	 if (document.getElementById("chkborder1").checked)  border  = "1"; 
	 if (document.getElementById("chkborder1b").checked) border2 = "1"; 
  }
  if (item == 2){
     obj = document.fchkbox2;
     if (document.getElementById("chkborder2").checked) border = "1"; 
     if (document.getElementById("chkborder2b").checked) border2 = "1";     
  }
         
  //for文でチェックボックスを１つずつ確認
  for( i=0; i<nData[item]; i++ )
  {  
    //チェックされているか確認する
    if( obj.elements[i].checked )
    {
      //変数strが空でない時、区切りのコンマを入れる
      if( str != "" ) str=str+",";
      
      //チェックボックスのvalue値を変数strに入れる
      str=str+obj.elements[i].value;
    }
  }

  //strが空の時、警告を出す
  if( str=="" ){
     alert( "どれか選択してください。" );
 
  }else{
//    alert( str + "が選択されました。" );

		if (item == 1) {

			if (newGraphMode == true){
				graph_draw (item,str,border,border2);
			} else {	    
				document.getElementById("graphContainer").src ="graph.php?p1="+str +"&p2="+fname[item]+"&p3="+border+"&p4="+border2;
    //alert(document.getElementById("graph").src);
			}
		}
		if (item == 2){
			if (newGraphMode == true){
				graph_draw (item,str,border,border2); 
			} else {	
				document.getElementById("graphContainer1").src ="graph.php?p1="+str+"&p2="+fname[item]+"&p3="+border+"&p4="+border2;
			}
		}
	}
}

// 全てのチェックボックをチェックする
function chBxOn(item,onoff){
  if (item == 1) { 
	  obj = document.fchkbox;
  }
  if (item == 2){
     obj = document.fchkbox2;   	  
  }
  for(i=0; i<nData[item]; i++) {
    if (item == 2 && onoff == true && i == 20) continue;
    obj.elements[i].checked = onoff;
  }
}

function check_sel(item,selchk)  {
	if (item == 1) {
		obj = document.fchkbox;
	}
	if (item == 2){
		obj = document.fchkbox2;   	  
	}

	var resArray = selchk.split(",");
	for (i =0;i<resArray.length;i++){
		n = parseInt(resArray[i],10);
		obj.elements[n-1].checked = true;
	}
  
}


function yosoku_cal(item ){


  //チェックされた項目を記録する変数
	var str="";
	if (item == 1) {
		obj = document.fchkbox;
		nTarget = document.querySelector('#target_event').value;
	}
	if (item == 2){
		obj = document.fchkbox2;
		nTarget = document.querySelector('#target_event1').value;
	}
         
  //for文でチェックボックスを１つずつ確認
  for( i=0; i<nData[item]; i++ )
  {  
    //チェックされているか確認する
    if( obj.elements[i].checked )
    {
      //変数strが空でない時、区切りのコンマを入れる
      if( str != "" ) str=str+",";
      
      //チェックボックスのvalue値を変数strに入れる
      str=str+obj.elements[i].value;
    }
  }

  //strが空の時、警告を出す
  if( str=="" ){
     alert( "どれか選択してください。" );
 
	}else{
//    alert( str + "が選択されました。" );

		if (item == 1) {
			data = "&p1="+fname[item]+"&p2="+str+"&p3="+nTarget;
//			alert(data);
	  //送信
	  		sendRequest( onloaded2_1,data,'GET','yosoku2.php',false,true );
		}   

		if (item == 2){
			data = "&p1="+fname[item]+"&p2="+str+"&p3="+nTarget;
//			alert(data);
	  //送信
	  		sendRequest( onloaded2_2,data,'GET','yosoku2.php',false,true );
		}
    }
}

	function onloaded2_1(res)
	{
		var text = 	res.responseText.split("//");
		document.querySelector('#yosoku_result_a').innerHTML = text[0];
		document.querySelector('#yosoku_result_b').innerHTML = text[1];	
	}
	function onloaded2_2(res)
	{
		var text = 	res.responseText.split("//");
		document.querySelector('#yosoku_result2_a').innerHTML = text[0];
		document.querySelector('#yosoku_result2_b').innerHTML = text[1];	
	}
function yosoku_sel(item)
{

  //チェックされた項目を記録する変数
	var str="";
	var data;
	if (item == 1) {
		radioList = document.getElementsByName("yradio");
		nTarget = document.querySelector('#target_event').value;
	}
	if (item == 2){
		radioList = document.getElementsByName("yradio2");
		nTarget = document.querySelector('#target_event1').value;
	}
         
  //for文でチェックボックスを１つずつ確認
  	for( i=0; i<radioList.length; i++ )
  	{  
    //チェックされているか確認する
    	if( radioList[i].checked )
    	{
      		//チェックボックスのvalue値を変数strに入れる
    		str=radioList[i].value;
   		}
  	}

	if (item == 1) {
		chBxOn(item,false);
		check_sel(item,String(nTarget));
		data = "&p1=<?= $fname; ?>&p2="+nTarget+"&p3="+str;
	  //送信
		sendRequest( onloaded_1,data,'GET','yosoku.php',false,true );

		
//		var form = new FormData();
//		form.append('p1',"<?= $fname; ?>");
//		form.append('p2',nData);
//		form.append('p3',str);
		
//		data = "?p1=<?= $fname; ?>&p2="+nData+"&p3="+str;
//		url = 'yosoku.php';
//		url = url + encodeURI(data);
//		var xhr = new XMLHttpRequest();
//		xhr.open('GET',url,false );
//		xhr.onload = function(e){
//			if (this.status == 200){
//				var result = this.response;
//				check_sel(1,result);
//			}
//		}
//		xhr.send();
		
		
	}   

	if (item == 2){
		chBxOn(item,false);
		check_sel(item,String(nTarget));
		data = "&p1=<?= $fname2; ?>&p2="+nTarget+"&p3="+str;
	  //送信
		sendRequest( onloaded_2,data,'GET','yosoku.php',false,true );

	}
    
}


</script>


</body>

</html>	
