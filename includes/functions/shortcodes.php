<?php

add_shortcode('button', function($a){
	$html = '';
	$link = isset($a['link']) ? $a['link'] : '';
	$text = isset($a['text']) ? $a['text'] : '';

	if(!empty($link) && !empty($text)){
		$html .= "<a href='$link' class='btn'>$text</a>";
	}

	return $html;
});