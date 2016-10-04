var newGraphMode = false;
var currentSelTab = 2;
var firstDisplay  = true;
var fname ={1:"waru.csv",2:'waru1.csv'};
var chart_data ={
	
			width: 800,
			height:600,
			title:{
				text: "ワルぽん100位ポイント推移",
				fontSize: 20,
			},   
                        animationEnabled: false,  
			axisY:{ 
				title: "Point (G)",
				includeZero: false ,
				titleFontSize: 16,
				labelFontSize: 16,   
				 gridThickness: 1, 
				 interlacedColor: "#F8F8F8"                
			},
			axisX: {
				title: "Day",
				interval: 1,
				titleFontSize: 16,
				labelFontSize: 16,
				maximum: 7.0,
				minimun: 0.5,
			},
			toolTip: {
				shared: false,
				content: function(e){
					var body = new String;
					var head ;
					var str;
					var week=['月曜','火曜','水曜','木曜','金曜','土曜','日曜','日曜'];
					var day =parseInt(e.entries[0].dataPoint.x);
					var time = e.entries[0].dataPoint.x - day;
					if (time == 0) time_s = '0時';
					if (time == 0.75) time_s = '18時';
					if (day == 7) time_s = '24時';
					for (var i = 0; i < e.entries.length; i++){
						var  str = "<span style= 'color:"+e.entries[i].dataSeries.color + "'> " + e.entries[i].dataSeries.name + "</span>: <strong>"+  e.entries[i].dataPoint.y + "</strong>G <br/>" ; 
						body = body.concat(str);
					}
					head = "<span style = 'color:DodgerBlue; '><strong> "+ week[day]+' '+time_s+ "</strong></span><br/>";

					if (e.entries[0].dataPoint.x== 0.5 || e.entries[0].dataPoint.x== 7.5) {
						head = "<span style = 'color:DodgerBlue; '><strong> 背景ボーダー" + "</strong></span><br/>";
					}
					if (e.entries[0].dataPoint.x== 0.6 || e.entries[0].dataPoint.x== 7.6) {
						head = "<span style = 'color:DodgerBlue; '><strong> レアボーダー" + "</strong></span><br/>";
					}					
										
					return (head.concat(body));
				}
			},
//			data: datax,
          legend :{
          	 verticalAlign: "top",
				horizontalAlign :"left",
//				itemWidth: 200,
				itemMaxWidth: 900,
				maxWidth: 600,
				maxHeight: 900,
				itemWrap: false,
				fontSize: 14,
				fontColor: "blue",
			dockInsidePlotArea: true ,
            cursor:"pointer",
            itemclick : function(e) {
              if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
				e.dataSeries.visible = false;
              }
              else{
				e.dataSeries.visible = true;
              }
              chartx.render();
            }
          }
          
		};

var datax=[];
var datax1=[];
var chart=[];
var charx;
var gcolor = ["black","blue","red","green","brown","cyan","orange","gray","pink","tan","purple","navy","violet",
				"saddlebrown","chocolate","lightskyblue","fuchsia","cornflowerblue","darkcyan","turquoise","darkred",
				"darkseagreen","crimson","dodgerblue","darkkhaki","powderblue","hotpink","darkslategray","khaki","lightsalmon",
				"lightblue","lime","maroon","mediumorchid","darkolivegreen","midnightblue","slateblue","orangered",
				"paleturquoise","darkturquoise","lightcoral","peru","darkslateblue","chartreuse","sandybrown","yellowgreen"];


function graph_init(){

   chart[0] = new CanvasJS.Chart("chartContainer",chart_data);
   chart_data2=clone(chart_data);
   chart[1] = new CanvasJS.Chart("chartContainer1",chart_data2);

}

function graph_draw(item,sel,border1,border2){
//		delete data.data
		datax1=[];
		sel_item = sel.split(",");
		var yamax = 7000000;
		if (item ==1){
			var wdata = waru_data;
			ymax = 7000000;
		}
		if (item ==2){
			var wdata = waru_data1;
			ymax = 12500000;
			if (sel_item.indexOf('21') >= 0){
				ymax = 45000000;
			}

		}	
		var cnt = 0;
		for (i=0;i<nData[item];i++){
			if (i+1 != sel_item[cnt]) continue;
			var dataPoints=[];
			for (j=0;j<14;j++){
				if (wdata[j+1][i+1]=="-") {ydata = null;}
				else ydata = parseFloat(wdata[j+1][i+1]);
				dataPoints.push({x: parseFloat(wdata[j+1][0]), y: ydata});					
			}
			datax1.push({type:"line",showInLegend: true,name: wdata[0][i+1],color:gcolor[i+1],dataPoints:dataPoints});
			cnt++;
		}
		
		if (border1=='1'){
			cnt = 0;
			for (i=0;i<nData[item];i++){
			if (i+1 != sel_item[cnt]) continue;
			var dataPoints=[];
			if (wdata[15][i+1]!="-") {
				 ydata = parseFloat(wdata[15][i+1]);	
				dataPoints.push({x: parseFloat(0.5), y: ydata});	
				dataPoints.push({x: parseFloat(7.5), y: ydata});					
			}
			datax1.push({type:"line",markerType:"circle",lineThickness: 1,showInLegend: false,name: wdata[0][i+1],color:gcolor[i+1],dataPoints:dataPoints});
			cnt++;
			}		
		}
		
		if (border2=='1'){
			cnt = 0;
			for (i=0;i<nData[item];i++){
			if (i+1 != sel_item[cnt]) continue;
			var dataPoints=[];
			if (wdata[16][i+1]!="-") {
				 ydata = parseFloat(wdata[16][i+1]);	
				dataPoints.push({x: parseFloat(0.6), y: ydata});	
				dataPoints.push({x: parseFloat(7.6), y: ydata});					
			}
			datax1.push({type:"line",markerType:"circle",lineThickness: 1,showInLegend: false,name: wdata[0][i+1],color:gcolor[i+1],dataPoints:dataPoints});
			cnt++;
			}		
		}
		chartx=chart[item-1];
		chart_data.data = datax1;
		chart_data2.data = datax1;	
		chart[item-1].options.axisY.maximum = ymax;
		chart[item-1].options.axisX.minimum = 0.5;
		chart[item-1].options.axisX.maximum = 7.1;		
		//chart.render();
		chart[item-1].render();

}		

// アイテムをチェックしたとき呼ばれる
function itemCheck( item ) {
	if (newGraphMode == true){
		boxCheck(item );
	}	
}
function graph_select(item){
	boxCheck(item );
}

var waru_data = null;
var waru_data1 = null;
var nData=[];
var event_ongoing=[];
function data_load(){

		var form = new FormData();
		form.append('file',fname[1]);
		
//		data = "?p1=<?= $fname; ?>&p2="+nData+"&p3="+str;
		url = 'warudata.php';
		var xhr = new XMLHttpRequest();
		xhr.open('POST',url,false );
//		xhr.responseType='json';
		xhr.onload = function(e){
			if (this.status == 200){	
				waru_data = JSON.parse(this.response);
				nData[1] = waru_data[0].length-1;
				event_ongoing[1]= false;
				if (waru_data[14][nData[1]] == '-') event_ongoing[1]= true; 
			}
		};
		xhr.send(form);
		

		var form = new FormData();
		form.append('file',fname[2]);
		
		url = 'warudata.php';
		var xhr = new XMLHttpRequest();
		xhr.open('POST',url,false );
//		xhr.responseType='json';
		xhr.onload = function(e){
			if (this.status == 200){	
				waru_data1 = JSON.parse(this.response);
				nData[2] = waru_data1[0].length-1;
				event_ongoing[2]= false;
				if (waru_data1[14][nData[2]] == '-') event_ongoing[2]= true; 

			}
		};
		xhr.send(form);
}

    ////
	// 受信時に起動するコールバック関数
	//
	// @sample onloaded(res)
	// @param res レスポンス
	//
	function onloaded_1(res)
	{
//	  alert( "今受信しました : " + res.responseText  );
      check_sel(1,res.responseText);
      itemCheck(1);
	}
	function onloaded_2(res)
	{
//	  alert( "今受信しました : " + res.responseText  );
      check_sel(2,res.responseText);
     itemCheck(2);
	}

	////
	// 送信処理用関数
	//
	// @sample sendData(true)
	// @param async	非同期ならtrue 同期ならfalse
	//
	function sendData(async)
	{
	//alert( "今受信しました ");
	  //送信
	  sendRequest( onloaded,'','GET','yosoku.php',async,true );
	}   
	
	function change_mode()
{
	var chk = document.querySelector('#graphmode').checked;
	if (chk == true){
		document.getElementById("graphContainer").style.display="none";
		document.getElementById("chartContainer").style.display="block";
		document.getElementById("graphContainer1").style.display="none";
		document.getElementById("chartContainer1").style.display="block";

	} else {
		document.getElementById("graphContainer").style.display="block";
		document.getElementById("chartContainer").style.display="none";
		document.getElementById("graphContainer1").style.display="block";
		document.getElementById("chartContainer1").style.display="none";

	}
	newGraphMode = chk;
	if (currentSelTab == 1 || currentSelTab == 2) graph_select(currentSelTab);
}

	disp_flag = 0;
	function latestDisplay (){
		//return ;
		disp_flag = 1- disp_flag;
		if (disp_flag) {
			if (event_ongoing[1]==true){
				var ss = document.getElementsByClassName("lchkbox");
				ss[ss.length-1].style.color="crimson";
				ss[ss.length-1].style.fontWeight = "bold";
			}
			if (event_ongoing[2]==true){
				var ss = document.getElementsByClassName("lchkbox2");
				ss[ss.length-1].style.color="crimson";
				ss[ss.length-1].style.fontWeight = "bold";
			}
		} else {
			if (event_ongoing[1]==true){
				var ss = document.getElementsByClassName("lchkbox");
				ss[ss.length-1].style.color="black";
				ss[ss.length-1].style.fontWeight = "bold";
			}
			if (event_ongoing[2]==true){
				var ss = document.getElementsByClassName("lchkbox2");
				ss[ss.length-1].style.color="black";
				ss[ss.length-1].style.fontWeight = "bold";
			}
		}
	}