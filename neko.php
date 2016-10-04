<?php

include ("jpgraph/jpgraph.php");
include ("jpgraph/jpgraph_bar.php");

  $fname = "neko.csv";
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
  
$kenzyou=array(15000,16000,17000,18500);
$kenzyou_color=array('dodgerblue','aquamarine3','goldenrod2','firebrick');

  $nData = $i-1;
  for ($k =0 ;$k<$nData;$k++){
    $ydata[$k] = $vdata[$k][1];
    $xdata[$k] = $vdata[$k][0];
    $i = 0;
    $cdata[$k]='red';
    foreach ($kenzyou as $d){
      if ($d == $vdata[$k][2]){
        $cdata[$k] = $kenzyou_color[$i];
        break;
      }
      $i++;
    }    
  }


// Create the graph. These two calls are always required
$graph = new Graph(800,600,'auto');    
$graph->SetScale("textlin");

$graph->Set90AndMargin(100,30,50,30);

// Add a drop shadow
$graph->SetShadow();

// Adjust the margin a bit to make more room for titles
//$graph->img->SetMargin(40,30,20,40);

$graph->xaxis->SetTickLabels($xdata);
$graph->SetFrame(true);


// Create a bar pot
$bplot = new BarPlot($ydata);
$bplot->SetWidth(0.6);
// Adjust fill color
//21～23行目を書き換える
$graph->Add($bplot);
$bplot->SetFillColor($cdata);
$bplot->value->Show();
$bplot->value->SetFormat('%d');

$graph->xaxis->SetFont(FF_GOTHIC,FS_NORMAL,10);
$graph->yaxis->SetFont(FF_GOTHIC,FS_NORMAL,10);

// Setup the titles
$graph->title->Set("ねこさま背景獲得者数(人）");
$graph->xaxis->title->Set("");
$graph->yaxis->title->Set("");


$graph->title->SetFont(FF_GOTHIC,FS_NORMAL,16);
$graph->yaxis->title->SetFont(FF_GOTHIC,FS_NORMAL);
$graph->xaxis->title->SetFont(FF_GOTHIC,FS_NORMAL);

// Display the graph
$graph->Stroke();





?>