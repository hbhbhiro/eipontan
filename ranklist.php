<!DOCTYPE html>
<html lang="ja">
<head>
	<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, maximum-scale=10.0, user-scalable=yes">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex">
	<title>えいぽんたん</title>
</head>


<body>

<script type="text/javascript">
  for (i=1;i<=100;i++){
  	x = 20;
  	step = 1;
  	for (j=1;j<i;j++){
  		if ((j % 6) == 0) step++;
  		x += step;
  		
  	}
  	document.write(i+" : "+ x +"<br>");
  }
	
</script>

</body>
</html>	