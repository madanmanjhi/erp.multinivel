<style>

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
	margin: 0; 
	padding: 0; 
	height: 100%; 
	overflow: hidden;
	background: <?php echo $themeSettings['tab_background'];?>;
	direction: <?php echo $dir;?>;
}

.cometchat_ts {
	color: <?php echo $themeSettings['tab_border_light'];?>;
	font-size: 10px;
	padding-top: 2px;
	padding-left: 5px;
	cursor: default;
}

.cometchat_ts:hover {
	color: <?php echo $themeSettings['tab_color_self'];?>;
	font-size: 10px;
	padding-top: 2px;
	padding-left: 5px;
}

.cometchat_chatboxmessage {
	margin-left: 1em;
	position: relative;
        <?php if($rtl):?>
	float: right;
        width: 100%;
	<?php endif;?>
}

.cometchat_chatboxmessagefrom {
	<?php if(!$rtl):?>
	margin-left: -1em;
	<?php else: ?>
        float: right;
        <?php endif;?>
	font-weight: bold;
}

.cometchat_textarea {
	border: 0px;
	height: 18px;
	overflow: hidden;
	font-family: <?php echo $themeSettings['tab_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
	color: <?php echo $themeSettings['tab_color_self'];?>;
	background: <?php echo $themeSettings['tab_background'];?>;
	outline:none;
	resize: none;
}

.cometchat_userscontentavatarimage {
	height: 18px;
	width: 18px;
}

.cometchat_userscontentavatar {
	display: block;
	float: <?php echo $left;?>;
	padding-bottom: 1px;
	padding-<?php echo $left;?>: 5px;
	padding-top: 1px;
	
}

.cometchat_userlist_hover {
	background-color: <?php echo $themeSettings['tab_title_backgroud_light'];?> !important;
	color: <?php echo $themeSettings['tab_color'];?>;
	
}

.cometchat_tooltip_content {
	background-color: <?php echo $themeSettings['tooltip_background'];?>;
	color: <?php echo $themeSettings['tooltip_color'];?>;
	font-family: <?php echo $themeSettings['tooltip_font_family'];?>;
	font-size: <?php echo $themeSettings['tooltip_font_size'];?>;
	padding: 5px;
	
	white-space: nowrap;
}

.cometchat_userlist {
	cursor: pointer;
	height: 20px;
	line-height: 100%;	
	padding-top: 1px;
	padding-bottom: 1px;
	font-family: <?php echo $themeSettings['tab_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
	color: <?php echo $themeSettings['tab_color_self'];?>;
	float:left;
}

.cometchat_userscontentname {
	float: left;
	padding-bottom: 3px;
	padding-left: 5px;
	padding-top: 4px;	
}


.cometchat_chatroomselected {
	background-color: <?php echo $themeSettings['tab_title_backgroud_light'];?>;
	font-weight: bold;
}

.chatbox {
	float: right;
	width: 149px;
	height: 300px;
	padding-top: 5px;
}

#chatrooms {
	height: 271px !important;
	overflow-y: auto;
	overflow-x: hidden;
}

.topbar {
	width: 100%;
	font-family: <?php echo $themeSettings['tab_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
	float: left;
	background-color: #6D6E71;
}

.topbar_text {
	padding:10px;
	background-color: <?php echo $themeSettings['tab_sub_background'];?>;
	border-bottom: 1px solid <?php echo $themeSettings['tab_border_light'];?>;
	overflow-x: hidden;
	overflow-y: hidden;
	color: <?php echo $themeSettings['tab_sub_color'];?>;
}

.topbar_text a {
	color: <?php echo $themeSettings['tab_sub_color'];?>;
}

.welcomemessage {
	float: <?php echo $right;?>;
}

#plugins {
	float: <?php echo $left;?>;
}
 
ol.tabs {
	height: 24px;
	overflow: hidden;
}

ol, ul {
	list-style-image: none;
	list-style-position: outside;
	list-style-type :none;
}

.tabs li {
	float: <?php echo $left;?>;
	margin-right: 10px;
}

.tabs li a {
	background-color: #6D6E71;
	color: <?php echo $themeSettings['tab_title_color'];?>;
	display: block;
	font-size: 110%;
	font-weight: bold;
	margin-bottom: 0;
 	outline-color: -moz-use-text-color;
	outline-style: none;
	outline-width: medium;
	padding: 5px 7px;
	text-decoration: none;
	text-transform: lowercase;
}

.tabs li a:hover {
	background-color: <?php echo $themeSettings['tab_border_lighter'];?>;
	color: <?php echo $themeSettings['tab_color'];?>;
}


.tab_selected a {
	background-color: <?php echo $themeSettings['tab_sub_background'];?> !important;
	color: <?php echo $themeSettings['tab_color'];?> !important;
}

.content_div {
	font-family: <?php echo $themeSettings['tab_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
}

.content_div a {
	color: <?php echo $themeSettings['tab_link_color'];?>;
}

.lobby_rooms {
	overflow: auto;
	overflow-y: auto;
	overflow-x: hidden;
}

.lobby_room {
	border-bottom: 1px solid <?php echo $themeSettings['tab_border_light'];?>;
	color: <?php echo $themeSettings['tab_color_self'];?>;
	display: block;
	font-family: <?php echo $themeSettings['tab_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
	padding-bottom: 7px;
	padding-top: 7px;
	width: 100%;
	cursor: pointer;
	height: 15px;
}

#currentroom {
	padding: 0px;

}

#currentroom_users {
	width: 185px;
	float: left;
	border-<?php echo $left;?>: 1px dotted <?php echo $themeSettings['tab_border_light'];?>;
	padding-left: 5px;
	overflow-y: auto;
	overflow-x: hidden;
}

#currentroom_convo {
	overflow-y: auto;
	overflow-x: hidden;
}

#currentroom_convotext {
	color: <?php echo $themeSettings['tab_color'];?>;
	padding: 5px;
}

.cometchat_tabcontentinput {
	padding: 4px 5px 0px 25px;
	border: 0px;
	border-top: 1px solid <?php echo $themeSettings['tab_border_light'];?>;
	background: url(themes/<?php echo $theme;?>/images/cometchat.png) no-repeat top left;
	background-position: 5px -23px;
	outline: none;
}

#currentroom_left {
	float: <?php echo $left;?>;
	<?php if($rtl):?>
	margin-left: 2px;      
	<?php endif;?>
}

#container { 
    position: relative; /* needed for footer positioning*/ 
    margin: 0 auto; /* center, not in IE5 */ 
    width: 100%; 
    height: auto !important; /* real browsers */ 
    height: 100%; /* IE6: treaded as min-height*/ 
    min-height: 100%; /* real browsers */ 
} 

#lobby {
	height: 100%;
}

.cometchat_chatboxalert {
	color: <?php echo $themeSettings['tab_link_color'];?>;
}

.create {
	padding: 5px;
}

.create_name {
	width: 70px;
	text-align: right;
	float: <?php echo $left;?>;
	padding-right: 10px;
}
.create_value {
	float: left;
}

.create_input {
	width: 200px;
	font-family: <?php echo $themeSettings['tab_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
}

.password_hide {
	display: none;
}

.invitesuccess {
	padding-left: 5px;
	padding-top: 85px;
	padding-bottom: 5px;
	text-align: center;
	font-weight: bold;
}

.lobby_room_1 {
	float: <?php echo $left;?>;
        overflow: hidden;
        padding-<?php echo $left;?>: 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 250px;
}

.lobby_room_2 {
	float: <?php echo $right;?>;
	padding-<?php echo $right;?>:10px;
	width:70px;
	text-align:right;
}

.lobby_room_3 {
	float: <?php echo $right;?>;
	padding-<?php echo $right;?>:10px;
}

.lobby_room_4 {
	float: <?php echo $right;?>;
	padding-<?php echo $right;?>:10px;
}

.cometchat_plugins {
	font-size: 9px;
	padding-<?php echo $right;?>: 5px;
	
}

.cometchat_pluginsicon {
	background: url(<?php echo BASE_URL;?>themes/<?php echo $theme;?>/images/cometchat_plugin_icon.png) no-repeat top left;
	cursor: pointer;
	margin-<?php echo $right;?>: 5px;	
	float: <?php echo $left;?>;
	
}

.cometchat_pluginsicon:hover {
	-moz-opacity: 0.6;
	opacity: 0.6;
}

.cometchat_pluginsicon_divider {
	margin-right: 5px;
	
}

.cometchat_ts {
	display: none;
        <?php if($rtl): ?>
        float: left;
        <?php endif;?>
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
	border-bottom: 1px solid <?php echo $themeSettings['tab_border_light'];?>;
	border-right: 1px solid <?php echo $themeSettings['tab_border'];?>;
	background-color: <?php echo $themeSettings['tab_background'];?>;
	color: <?php echo $themeSettings['tab_color'];?>;
	font-family: <?php echo $themeSettings['tab_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
	padding: 10px 10px;
	

	height: 110px;
	overflow-x: hidden;
	overflow-y: auto;
}

.container_body.embed {
	border: 0px;
	padding: 10px;
}

.container_title.embed {
	display: none;
}

.invite_1 {
	cursor: pointer;
	position: relative;
	height: 30px;
	float: left;
	width: 159px;
	border: 1px dotted <?php echo $themeSettings['tab_border_light'];?>;
	padding: 3px;
	margin: 4px;
}

.invite_2 {
	float: left;
}

.invite_3 {
	float: left;
	padding-<?php echo $left;?>: 10px;
        max-width: 100px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
}

.invite_4 {
	position: absolute;
	right: 4px;
	top: 4px;
}

.invite_5 {
	color: <?php echo $themeSettings['tab_sub_color'];?>;
}
.invite_name{
	text-overflow: ellipsis;
	overflow: hidden;
	width: 100px;
	float: left;
	white-space:nowrap;
}

.invitebutton {
	background-color: <?php echo $themeSettings['tab_title_background'];?> !important;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $themeSettings['tab_title_gradient_start'];?>', endColorstr='<?php echo $themeSettings['tab_title_gradient_end'];?>');
	background: -webkit-gradient(linear, left top, left bottom, from(<?php echo $themeSettings['tab_title_gradient_start'];?>), to(<?php echo $themeSettings['tab_title_gradient_end'];?>));
	background: -moz-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	background: -ms-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	background: -o-linear-gradient(top, <?php echo $themeSettings['tab_title_gradient_start'];?>, <?php echo $themeSettings['tab_title_gradient_end'];?>);
	border: 1px solid <?php echo $themeSettings['tab_title_border'];?>;
	color: <?php echo $themeSettings['tab_title_color'];?>;
	cursor: pointer;
	font-family: <?php echo $themeSettings['tab_title_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_font_size'];?>;
	font-weight: bold;
	text-shadow: 1px 1px 0 <?php echo $themeSettings['tab_title_text_background'];?>;
	padding: 3px;
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

.container_sub {
	border-left: 1px solid <?php echo $themeSettings['tab_border'];?>;
	border-bottom: 1px solid <?php echo $themeSettings['tab_border'];?>;
	border-right: 1px solid <?php echo $themeSettings['tab_border'];?>;
	color: <?php echo $themeSettings['tab_sub_color'];?>;
	padding: 5px;
}

.container_sub.embed {
	border: 0px;
	margin-top: 5px;
}

.container_body.embed {
	height: 130px;
}

.containermessage {
	font-family: <?php echo $themeSettings['tab_title_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_title_font_size_large'];?>;
        padding: 10px;
}

.chatroom_avatar {
	float:left;
	height:50px;
	width:50px;
}

.control_buttons {
	float:left;
	line-height:50px;
	margin-left:20px;
}

.cometchat_userlist .cometchat_userscontentname {
	text-overflow: ellipsis;
	width: 138px;
	white-space: nowrap;
	overflow: hidden;
}

#currentroomtab a {
	text-overflow: ellipsis;
	width: auto;
	white-space: nowrap;
	overflow: hidden;
	max-width: 100px;
}

.delete_msg {
	cursor: pointer;
	font-size: 9px;
	width: 40px;
	color:#ccc;
	display:none;
	padding-left: 5px;
	padding-top: 2px;
	vertical-align:1px;
	
}
.delete_msg:hover {
	color:#333;
	display:inline;
	cursor: pointer;
	font-size: 9px;
	width: 40px;
	padding-left: 5px;
	padding-top: 2px;
	vertical-align:1px;
}

.hoverbraces{
	text-decoration:none;	
}

.cometchat_subsubtitle {
	color: <?php echo $themeSettings['tab_sub_color'];?>;
	font-family: <?php echo $themeSettings['tab_title_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_title_font_size'];?>;
	line-height: 1.3em;
	cursor: default;
	margin-top: 5px;
	margin-bottom: 5px;
	overflow: hidden;
	white-space: nowrap;
	float:left;
}

.cometchat_subsubtitleusers{
	color: <?php echo $themeSettings['tab_sub_color'];?>;
	font-family: <?php echo $themeSettings['tab_title_font_family'];?>;
	font-size: <?php echo $themeSettings['tab_title_font_size'];?>;
	line-height: 1.3em;
	cursor: default;
	margin-top: 10px;
	margin-bottom: 5px;
	overflow: hidden;
	white-space: nowrap;
	float:left;
}

.cometchat_subsubtitle_top {
	margin-top: 0px;
	padding-top: 0px;
}

.cometchat_subsubtitle .hrleft, .cometchat_subsubtitleusers   .hrleft{
	display: inline-block;
	width: 5px;
	border: 0;
	background-color: <?php echo $themeSettings['tab_border_light'];?>;
	height: 1px;
	margin-right: 5px;
	margin-bottom: 3px;
}

.cometchat_subsubtitle .hrright,.cometchat_subsubtitleusers   .hrright {
	display: inline-block;
	width: 200px;
	border: 0;
	background-color: <?php echo $themeSettings['tab_border_light'];?>;
	height: 1px;
	margin-left: 5px;
	margin-bottom: 3px;
}

.file_image {
	max-width:125px;
	padding-left: 6%;
}

.file_video {
	max-width:125px;
	height:120px;
}

.imagemessage {
	display: inline-block;
	margin-bottom: 3px;
	margin-top: 3px;
}

.cometchat_avchat{ background-position: -2px -27px; width: 16px; height: 16px; } 
.cometchat_block{ background-position: 0 -66px; width: 16px; height: 16px; } 
.cometchat_broadcast{ background-position: 0 -132px; width: 16px; height: 16px; } 
.cometchat_chathistory{ background-position: 0 -198px; width: 16px; height: 16px; } 
.cometchat_chattime{ background-position: 0 -264px; width: 16px; height: 16px; } 
.cometchat_clearconversation{ background-position: 0 -330px; width: 16px; height: 16px; } 
.cometchat_style{ background-position: 0 -396px; width: 16px; height: 16px; } 
.cometchat_filetransfer{ background-position: 0 -462px; width: 16px; height: 16px; } 
.cometchat_games{ background-position: 0 -528px; width: 16px; height: 16px; } 
.cometchat_handwrite{ background-position: 0 -594px; width: 16px; height: 16px; } 
.cometchat_report{ background-position: 0 -660px; width: 16px; height: 16px; } 
.cometchat_save{ background-position: 0 -726px; width: 16px; height: 16px; } 
.cometchat_screenshare{ background-position: 0 -792px; width: 16px; height: 16px; } 
.cometchat_smilies{ background-position: 0 -858px; width: 16px; height: 16px; } 
.cometchat_transliterate{ background-position: 0 -924px; width: 16px; height: 16px; } 
.cometchat_whiteboard{ background-position: 0 -990px; width: 16px; height: 16px; } 
.cometchat_writeboard{ background-position: 0 -1056px; width: 16px; height: 16px; }

.cometchat_smiley {
    display: inline-block;
    vertical-align: -6px;
}

.passwordbox_body {
    text-align: left;
	padding-bottom: 10px;
}

#passwordBox {
	margin-right: 5px;
}

.talkindicator {
	background: none repeat scroll 0 0 #999999;
	border-radius: 4px;
	color: #FFFFFF;
	cursor:	pointer;
	display: block;
	font-size: 11px;
	padding: 3px 5px;
	position:absolute;
	right: 212px;
	text-decoration: none;
	top: 75px;
	z-index: 99999;
}

</style>