<?php
class Comm_Common{
	/**
		做提示功能使用，3秒自动消失   不能使用
	**/
	public static function autoHideHtml($msg){
		$str = '<script>$(function(){$(".autoHide").hide(3000);});</script>';
		$str .= "<span class='autoHide'>".$msg."</span>";
		return $str;
	}
}