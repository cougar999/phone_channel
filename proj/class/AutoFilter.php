<?php
class AutoFilter{
	public $splitFunc = '|';
	public $splitArgs = ':';
	public function __construct($pra)
	{
		foreach((array)$pra as $k=>$v){
			$this -> $k = $v;
		}
	}
	public function __get($name)
	{
		$tmp  = explode($this->splitFunc,$name);
		$prop = end($tmp);
		$val  = $this->$prop;
		array_pop($tmp);
		foreach((array)$tmp as $item){
			$_tmp    = explode($this->splitArgs,$item);
			$_fun    = $_tmp[0];
			$_tmp[0] = $val;
			if(function_exists($_fun)) $val = call_user_func_array($_fun,$_tmp);
			else if(method_exists($this,$_fun)) $val = call_user_func_array(array($this,$_fun),$_tmp);
		}
		$this->$name=$val;
		return $val;
	}
	public function __set($name,$value)
	{
		return $this->$name=$value;
	}
	public function fstr($value)
	{
		return trim($value);
	}
	public function fint($value)
	{
		return intval($value);
	}
	public function dfval($val,$df){
		return !$val?$df:$val;
	}
}