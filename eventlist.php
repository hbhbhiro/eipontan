<!DOCTYPE html>
<html lang="ja">
<head>
	<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, maximum-scale=10.0, user-scalable=yes">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex">

	<title> えいぽんたんイベント一覧 </title>
</head>
<body>

<div>
	<?php
	function es($data){
	if (is_array($data)){
		return array_map(__METHOD__,$data);
	} else{
		return htmlspecialchars($data,ENT_QUOTES,"utf-8");
		}
		}
		

	$server = php_uname('n');
	if (strpos($server,'sakura.ne.jp')!==false){
		$host = 'mysql531.db.sakura.ne.jp';		
	} else {
		$host = 'localhost:3306';
	}
	
	$user = 'hbhbhiro';
	$password = 'hbhb-6601';
	$dbname = 'hbhbhiro_eipontan';


	
	$dsn = "mysql:host={$host};dbname={$dbname};charaset=utf8";

	try {
		$pdo = new PDO($dsn,$user,$password);
		$pdo->query('SET NAMES utf8');
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
//		echo "データベース{$dbname}に接続しました。";
		
		$sql = "SELECT * FROM event";
		$stm = $pdo->prepare($sql);
		$stm->execute();
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		
		echo "<b>";
		echo "<table border=\"1\" style=\"font-size:13px;width:1024px;\"> ";
		echo "<caption>イベント一覧表2015年6月～</caption>";
		echo "<thead style=\"background-color:#fff0cf;\"><tr>";
		echo "<th>","No","</th>";
		echo "<th>","イベント","</th>";
		echo "<th>","背景","</th>";
		echo "<th>","開始日","</th>";
		echo "<th>","種類","</th>";
		echo "<th>","備考","</th>";
		echo "</tr></thead>";	
		
		echo "<tbody>";
		foreach ($result as $row){
			$kind = es($row['kind']);
			if ($kind == "ねこさま") echo "<tr style=\"background-color:#ffe0ef\">";
			else echo "<tr>";
			$id = intval($row['id']);
			echo "<td>", es($row['id']),"</td>";
		
			$filename = sprintf('img/event_%d.jpg',$id);
			$result = file_exists($filename);
			if ($result ){
//				echo "<td><a href = \"{$filename}\"> ", es($row['name']),"</a></td>";
					
				echo "<td>","<a href=\"javascript:void(0)\" onclick=\" window.open('{$filename}', '_blank', 'width=600,scrollbars=yes,toolbar=no,menubar=no,directories=no,location=no'); \">";
				echo es($row['name']);
				echo "</a></td>"; 
			} else {
				echo "<td>", es($row['name']),"</td>";
			}	
			echo "<td>", es($row['haikei']),"</td>";
			echo "<td>", es($row['date']),"</td>";
			echo "<td>", es($row['kind']),"</td>";
			echo "<td>", es($row['extra']),"</td>";
			echo "</tr>";
		}
		
		echo "</b>";
		
		$pdo = NULL;
	} catch (Exception $e){
		echo '<span class="error">エラーがありました。</span><br>';
		echo $e->getMessage();
		exit();
	}
	?>
</div>		
</body>
</html>