<?php // content="text/plain; charset=utf-8"
require_once ("jpgraph/jpgraph.php");
require_once ("jpgraph/jpgraph_line.php");
//require_once ("jpgraph/jpgraph_scatter.php");

$color = array("black","blue","red","green","brown","cyan","orange","gray","pink","yellow2","purple","navy","violet",
				"brown3","burlywood4","cadetblue3","chartreuse3","cornflowerblue","darkcyan","darkolivegreen3","darkred",
				"darkseagreen","deepindigo","deepskyblue2","gold3","gray5","hotpink3","indigodye","khaki1","lightsalmon",
				"lightsteelblue1","limegreen","maroon","mediumorchid","mediumred","midnightblue","olivedrab1","orangered1",
				"paleturquoise","paleturquoise4","paleturquoise4","peru","purple1","turquoise1","yellow3","yellowgreen");

$p1 = $_GET["p2"];
if ($p1 != "waru.csv" && $p1 !="waru1.csv") exit;
$fname = $p1;
$item =1;
if ($p1=="waru1.csv") $item= 2;

$p1 = $_GET["p1"];
$parm = explode(",",$p1);
$i=0;
foreach( $parm as $x){
  $disp_on[$i]=intval($x);		///disp_onは1～
  $i++;
}
$nData=count($parm);

$disp_border = false;
$p2 = $_GET["p3"];
if ($p2 == "1"){
  $disp_border = true;
}

$disp_border2 = false;
$p2 = $_GET["p4"];
if ($p2 == "1"){
  $disp_border2 = true;
}
//  $nData = 7;

//  $disp_on = array(3,4,5,6,7,8,9);
  
//   $fname = "waru1.csv";
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

  for ($k =0 ;$k<$nData;$k++){
    for ($j=1;$j<15;$j++){
      $ydata[$k][$j-1] = $vdata[$j][$disp_on[$k]];
    }
    $border[$k][0]= $vdata[15][$disp_on[$k]];
    $border[$k][1] = $border[$k][0];
    $border2[$k][0]= $vdata[16][$disp_on[$k]];
    $border2[$k][1] = $border2[$k][0];
    
    
  }
$xdata = array(0.75,1,1.75,2,2.75,3,3.75,4,4.75,5,5.75,6,6.75,7);
//$ydata = array(11,3,8,12,5,1,9,13,500,7);
//$y2data = array(354,200,265,99,111,91,198,225,293,251);
$xborder = array(0.5,7);

$y_max=6000000;
$y_tick=500000;
if ($item==2){
    $y_max=13000000;
	$y_tick=1000000;
  if (in_array(21,$disp_on)){
    $y_max=43000000;
	$y_tick=5000000;
  }
}




// Create the graph and specify the scale for both Y-axis
$graph = new Graph(800,600,"auto");

$graph->SetScale("linlin",0,$y_max,0,7);
$graph->xaxis->scale->ticks->Set(1,0.5);
//$graph->SetY2Scale("lin");
$graph->SetShadow();

// Adjust the margin
$graph->img->SetMargin(80,15,20,150);

$graph->SetFrame(true);


// Adjust the axis color
//$graph->y2axis->SetColor("orange");
$graph->yaxis->SetColor("blue");

$graph->yaxis->HideLine(true);
$graph->yaxis->HideTicks(false,false);
$graph->yaxis->scale->ticks->Set($y_tick);

$graph->xaxis->HideLine(false);
$graph->xaxis->HideTicks(true,false);



$graph->title->setFont(FF_GOTHIC, FS_NORMAL, 12);    
$graph->title->Set('ワルぽん100位ポイント推移');
$graph->xaxis->title->Set("Day");
$graph->yaxis->title->Set("G Point");


$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->legend->setFont(FF_GOTHIC, FS_NORMAL, 12); // 凡例フォント


for ($i = 0; $i < $nData; $i++){
// Create the two linear plot
$lineplot[$i]=new LinePlot($ydata[$i],$xdata);


// Set the legends for the plots
$lineplot[$i]->SetLegend($vdata[0][$disp_on[$i]]);
// Add the plot to the graph
$graph->Add($lineplot[$i]);

$lineplot[$i]->SetColor($color[$disp_on[$i]]);
$lineplot[$i]->SetWeight(2);

$lineplot[$i]->mark->SetType(MARK_FILLEDCIRCLE);
$lineplot[$i]->mark->SetFillColor($color[$disp_on[$i]]);



// Set the colors for the plots 
$lineplot[$i]->SetColor($color[$disp_on[$i]]);
$lineplot[$i]->SetWeight(2);



}

if ($disp_border){
  for ($i = 0; $i < $nData; $i++){
    $lineplot[$i]=new LinePlot($border[$i],$xborder);
    $graph->Add($lineplot[$i]);
    $lineplot[$i]->SetColor($color[$disp_on[$i]]);
    $lineplot[$i]->SetWeight(2);
    $lineplot[$i]->SetStyle("dashed");
  }
}

if ($disp_border2){
  for ($i = 0; $i < $nData; $i++){
    $lineplot[$i]=new LinePlot($border2[$i],$xborder);
    $graph->Add($lineplot[$i]);
    $lineplot[$i]->SetColor($color[$disp_on[$i]]);
    $lineplot[$i]->SetWeight(2);
    $lineplot[$i]->SetStyle("dotted");
  }
}



// Adjust the legend position
$graph->legend->SetFont(FF_GOTHIC, FS_NORMAL,10);
$graph->legend->SetFrameWeight(2);
$graph->legend->SetMarkAbsSize(9);
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->SetColumns(6);
$graph->legend->Pos(0.01,0.98,"left","bottom");
//$graph->legend->Pos(0.12,0.05,"left","top");
$graph->legend->SetLineSpacing(-6);
// Adjust the margin


// Display the graph
$graph->Stroke();


?>

