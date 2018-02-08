<script>

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * CometChat 
 * Copyright (c) 2014 Inscripts - support@cometchat.com | http://www.cometchat.com | http://www.inscripts.com
*/

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-weight: inherit;
	font-style: inherit;
	font-size: 100%;
	font-family: inherit;
	vertical-align: baseline;
    
}

html, body {
	overflow: hidden;
	background: <?php echo $themeSettings['tab_background'];?>;
	direction: <?php echo $dir;?>;
}

.SI-FILES-STYLIZED label.cabinet
{
	width: 200px;
	height: 30px;
	display: block;
	z-index: 1000;
	position: absolute;
 }

.SI-FILES-STYLIZED label.cabinet input.file
{
	position: relative;
	height: 100%;
	width: auto;
	opacity: 0;
	-moz-opacity: 0;
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
}

.container {
	width: 98%;
	margin: 0 auto;
	margin-top: 5px;
}

.container_title {
	background-color: <?php echo $themeSettings['tab_title_background'];?> !important;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $themeSettings['tab_title_gradient_start'];?>', endColorstr='<?php echo $themeSettings['tab_title_gradient_end'];?>');
	background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $themeSettings['tab_title_gradient_start'];?>), to(<?php echo $themeSettings['tab_title_gradient_end'];?>));
	background: -moz-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	background: -ms-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	background: -o-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	border-left: 1px solid <?php echo $themeSettings['tab_title_border'];?>;
	border-right: 1px solid <?php echo $themeSettings['tab_title_border'];?>;
	border-top: 1px solid <?php echo $themeSettings['tab_title_border'];?>;
	color: <?php echo $themeSettings['tab_title_color'];?>;
	font-family: <?php echo $themeSettings['tab_title_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_title_font_size_large'];?>;
	padding: 5px;
	font-weight: bold;
	padding-left: 10px;
	padding-bottom: 6px;
	text-shadow: 1px 1px 0 <?php echo $themeSettings['tab_title_text_background'];?>;
}

.container_body {
	border-left: 1px solid <?php echo $themeSettings['tab_border'];?>;
	border-bottom: 1px solid <?php echo $themeSettings['tab_border'];?>;
	border-right: 1px solid <?php echo $themeSettings['tab_border'];?>;
	background-color: <?php echo $themeSettings['tab_background'];?>;
	color: <?php echo $themeSettings['tab_color'];?>;
	padding: 5px;
	font-weight: normal;
	font-family: <?php echo $themeSettings['tab_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
	padding: 10px 10px;
}

.container_body_1 {
	padding-bottom:15px;
}

.container_body_2 {
	text-decoration: none;
	color: <?php echo $themeSettings['tab_color'];?>;	
	display: block;
	height: 30px;
}

.container_body_3 {
	position: absolute;
	width: 97px;
	height: 16px;
	top: 68px;
	text-align: center;
	color: <?php echo $themeSettings['tab_title_color'];?>;
	font-family: <?php echo $themeSettings['tab_title_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
	font-weight: bold;
	text-shadow: 1px 1px 0 <?php echo $themeSettings['tab_title_text_background'];?>;
	background-color: <?php echo $themeSettings['tab_title_background'];?> !important;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $themeSettings['tab_title_gradient_start'];?>', endColorstr='<?php echo $themeSettings['tab_title_gradient_end'];?>');
	background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $themeSettings['tab_title_gradient_start'];?>), to(<?php echo $themeSettings['tab_title_gradient_end'];?>));
	background: -moz-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	background: -ms-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	background: -o-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	border: 1px solid <?php echo $themeSettings['tab_title_border'];?>;
	cursor: pointer;
	padding: 5px;
	-moz-border-radius-topleft: 3px;
	-moz-border-radius-topright: 3px;	
	-moz-border-radius-bottomleft: 3px;
	-moz-border-radius-bottomright: 3px;
	-webkit-border-top-left-radius: 3px;
	-webkit-border-top-right-radius: 3px;
	-webkit-border-bottom-left-radius: 3px;
	-webkit-border-bottom-right-radius: 3px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	border-bottom-left-radius: 3px;
	border-bottom-right-radius: 3px;
}

.container_body_4 {
	
}

.container_body.embed {
	border: 0px;
	padding: 10px;
}

.container_title.embed {
	display: none;
}

.container_body_3.embed {
	top: 45px;
}

</script>