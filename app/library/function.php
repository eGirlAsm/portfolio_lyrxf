<?php
if ( ! function_exists('linkcss'))
{
	function linkcss($href = '',$index_page = FALSE)
	{
		if($index_page)
			return '<link href="'.url::baseUrl().$href.'" rel="stylesheet" type="text/css"/>';
		else
			return '<link href="'.$href.'" rel="stylesheet" type="text/css"/>';
		
	}
}


if( !function_exists('linkjs')){

	function linkjs($href= '',$index_page = FALSE){
		if($index_page)
			return '<script type="text/javascript" src="'.url::baseUrl().$href.'"></script>';
		else
			return '<script type="text/javascript" src="'.$href.'"></script>';
	}
}

?>