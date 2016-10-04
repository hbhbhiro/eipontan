
<?php
/**
* Copyright 2015 Sinri Edogawa.
*/
class LinearRegression
{
    private $xs;
    private $ys;
    private $element_count;

    private $a;
    private $b;

    private $a1;
    private $b1;

	private $r1;
	private $se;
		
    function __construct()
    {
        $this->xs=array();
        $this->ys=array();
        $this->element_count=0;
    }

    public function addElement($x,$y){
        $this->xs[]=$x;
        $this->ys[]=$y;
        $this->element_count+=1;
    }

    //y=a+bx
    public function getConstBase(){
        return $this->a;
    }
    public function getParamFactor(){
        return $this->b;
    }
	
	    //y=a+bx
    public function getConstBase1(){
        return $this->a1;
    }
    public function getParamFactor1(){
        return $this->b1;
    }
	
	public function getParameterCorrel(){
		return $this->r1;
	}
	public function getParameterStexy(){
		return $this->se;
	}
	
		
		
	public function computeLR(){
        if($this->element_count<=0)return false;
        if($this->element_count!=count($this->xs) || $this->element_count!=count($this->ys))return false;
        $x_sum=0;
        $y_sum=0;
        for ($i=0; $i < $this->element_count; $i++) { 
            $x_sum+=$this->xs[$i];
            $y_sum+=$this->ys[$i];
        }
        $x_ave=1.0*$x_sum/$this->element_count;
        $y_ave=1.0*$y_sum/$this->element_count;

        $up=0;
        $down=0;

        for ($i=0; $i < $this->element_count; $i++) { 
            $up+=($this->xs[$i]-$x_ave)*($this->ys[$i]-$y_ave);
            $down+=($this->xs[$i]-$x_ave)*($this->xs[$i]-$x_ave);
        }   
        if($down==0)return false;
        $this->b=$up/$down;
        $this->a=$y_ave-$x_ave*$this->b;

		$this->a1 = s_intercept($this->ys, $this->xs);
		$this->b1 = s_slope($this->ys, $this->xs);
        $this->r1 = s_correl($this->ys, $this->xs);
		$this->se = s_stexy($this->ys, $this->xs);
        return true;
    }
}

	/**
 	* 単純平均を取得する
 	*/
	function s_average($list) {
    	if(count($list) < 2 || !is_array($list)){
        	return false;
    	}
    	$count = count($list);
		$sum = 0;
    	for ($i = 0; $i < $count; $i++) {
        	$sum += $list[$i];
    	}
    	return $sum / $count;
	}
	/**
 	* 調和平均を取得する
 	*/
	function s_harmean($list) {
    	if(count($list) < 2 || !is_array($list)){
       	 return false;
    	}
		$sum = 0;
    	$count = count($list);
    	for ($i = 0; $i < $count; $i++) {
        	$sum += 1 / $list[$i];
    	}
    	return $count / $sum;
	}
	/**
 	* 分散を取得する
 	* (引数を母集団全体であると仮定して、母集団の分散を返す)
 	*/
	function s_varp($list) {
   	 if(count($list) < 2 || !is_array($list)){
   	     return false;
   	 }
    	$avg = s_average($list);
    	$dec = 0;
    	$count = count($list);
    	for($i = 0; $i < $count; $i++){
        	$dec += pow($avg - $list[$i], 2);
    	}
    	return $dec / $count;
	}
	/**
	* 標準偏差を取得する
 	*/
	function s_stdevp($list) {
    	if(count($list) < 2 || !is_array($list)){
        	return false;
    	}
    	return sqrt(s_varp($list));
	}

	/**
 	* 偏差値を取得する
 	*/
	function s_devp($list, $val) {
    	if(count($list) < 2 || !is_array($list)){
        	return false;
    	}
    	return 10 * ($val - s_average($list)) / s_stdevp($list) + 50;
	}	
	/**
 	* 相関係数を取得する
 	*/
	function s_correl($list_y, $list_x){
    	if(count($list_x) < 2 || count($list_y) < 2 || count($list_x) != count($list_y)){
        	return false;
    	}
    	$avg_x = s_average($list_x);
    	$stdevp_x = s_stdevp($list_x);
    	$avg_y = s_average($list_y);
   	 	$stdevp_y = s_stdevp($list_y);

    	$count = count($list_x);
    	for($i = 0; $i < $count; $i++){
        	$x = $list_x[$i] - $avg_x;
        	$y = $list_y[$i] - $avg_y;
        	$devsum[$i] = $x * $y;
    	}
   
    	$avg_devsum = s_average($devsum);
    	return $avg_devsum / ($stdevp_x * $stdevp_y);
	}
	
	/**
 	* 回帰直線のｙ切片を求める
 	* （[y = a * x + b]の[b]を求める）
 	*/
	function s_intercept($list_y, $list_x){
    	if(count($list_x) < 2 || count($list_y) < 2 || count($list_x) != count($list_y)){
        	return false;
    	}
		
		$x_sum = 0;
		$y_sum = 0;
		$xx_sum = 0;
		$xy_sum = 0;
		
    	$count = count($list_x);
    	for ($i = 0; $i < $count; $i++) {
       		$x = $list_x[$i];
        	$y = $list_y[$i];
        	$x_sum += $x;
        	$y_sum += $y;
        	$xx_sum += $x * $x;
        	$xy_sum += $x * $y;
    	}
    	$a = ($count * $xy_sum - $x_sum * $y_sum)/($count * $xx_sum - $x_sum * $x_sum);
    	$b = ($y_sum - $a * $x_sum)/$count;
   
    	return $b;
	}	
	/**
 	* 回帰直線の傾きを求める
 	* （[y = a * x + b]の[a]を求める）
 	*/
	function s_slope($list_y, $list_x){
    	if(count($list_x) < 2 || count($list_y) < 2 || count($list_x) != count($list_y)){
       	 	return false;
    	}

		$x_sum = 0;
		$y_sum = 0;
		$xx_sum = 0;
		$xy_sum = 0;
    	$count = count($list_x);
    	for ($i = 0; $i < $count; $i++) {
        	$x = $list_x[$i];
        	$y = $list_y[$i];
        	$x_sum += $x;
        	$y_sum += $y;
        	$xx_sum += $x * $x;
        	$xy_sum += $x * $y;
    	}

    	$a = ($count * $xy_sum - $x_sum * $y_sum)/($count * $xx_sum - $x_sum * $x_sum);
    	$b = ($y_sum - $a * $x_sum)/$count;
   
    	return $a;
	}
	
		function datex($x){return $x;}
	/**
 	* 回帰直線上の上昇率を求める
 	*/
	function s_rate($list_y, $list_x){
    	if(count($list_x) < 2 || count($list_y) < 2 || count($list_x) != count($list_y)){
        	return false;
    	}
    	$a = s_slope($list_y, $list_x);
    	$b = s_intercept($list_y, $list_x);
   
    	return ($a * $list_x[count($list_x) - 1] + $b) / ($a * $list_x[count($list_x) - 2] + $b);
	}

	function s_stexy($list_y, $list_x){
    	if(count($list_x) < 3 || count($list_y) < 3 || count($list_x) != count($list_y)){
        	return false;
    	}
    	$avg_x = s_average($list_x);
    	$stdevp_x = s_stdevp($list_x);
    	$avg_y = s_average($list_y);
   	 	$stdevp_y = s_stdevp($list_y);
		
		$slope =  s_slope($list_y, $list_x);
		$intercept = s_intercept($list_y, $list_x);

		$sum = 0;
    	$count = count($list_x);
    	for($i = 0; $i < $count; $i++){

        	$y = $list_y[$i] - ($slope * $list_x[$i] + $intercept);
        	$sum += ($y * $y);
    	}
   
    	$stexy = sqrt($sum/($count-2));
    	return $stexy ;
	}

?>