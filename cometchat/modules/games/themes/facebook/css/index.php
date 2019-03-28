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
    overflow: hidden;
    background: <?php echo $themeSettings['tab_background'];?>;
    direction: <?php echo $dir;?>;
    height:100%;
}

#container {
    <?php if (!$rtl):?>
        margin-right: 2px;
    <?php endif;?>
    height:100%;
}

#categories {
    float: right;
    margin: 5px;
    text-transform: capitalize !important;
}

#topcont {
    background-color: #EEE;
    border-bottom: 1px solid #CCC;
    float: left;
    width: 100%;
}

.gamecontainer {
    float:left;
    width: 100%;
    <?php if (!$sleekScroller):?>
        width: 358px;
    <?php endif;?>
}

#games {
    width: 100% !important;
    height: 100% !important;
    float: left;
    overflow-y:auto;
    overflow-x:hidden;
    position:relative;    
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

#games img {
    width: 80px;
    height: 80px;
    margin-bottom: 2px;
    border-radius:5px;
    -webkit-border-radius: 5px; 
    -moz-border-radius: 5px;   
}

.gamelist {	
    <?php if ($sleekScroller):?>	
        padding:2px;
    <?php endif;?>	
    margin:5px 2px 0px 8px;
    text-align: center;
    overflow:hidden;
    text-overflow:ellipsis;
    font-family: <?php echo $themeSettings['tab_font_family'];?>;
    font-size: <?php echo $themeSettings['tab_font_size_small'];?>;
    color: <?php echo $themeSettings['tab_color'];?>;
    float: left;
    width: 100px;
    height: 100px;
    cursor: pointer;
    position:relative;
}

.gamelist:hover {
    filter: alpha(opacity=60);
    -moz-opacity: 0.6;
    opacity: 0.6;
}

.title {
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}

#loader {
    position: absolute;
    top: 10px;
    right: 15px;
    background-image: url(themes/<?php echo $theme;?>/images/loader.gif);
    width: 16px;
    height: 16px;
    display: none;
}

.custom-dropdown {
    position: relative;
    width: 130px;
    border: 1px solid #A9A9A9;
    background: #FFF;
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    font-family: <?php echo $themeSettings['tab_font_family'];?>;
    font-size: <?php echo $themeSettings['tab_font_size_small'];?>;
    color: <?php echo $themeSettings['tab_color'];?>;
}

.custom-dropdown .selected, #optionList li {
    display: block;
    font-size: 11px;
    font-weight: 400;
    text-transform: capitalize;
    line-height: 1;
    padding: 4px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    cursor: pointer;    
}

.carat {
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -3px;
    background-image: url(images/dropDownArrow.png);
    background-repeat: no-repeat;
    height: 12px;
    width: 12px;
    z-index: 1;
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    transform: rotate(0deg);
    -webkit-transform-origin: 50% 25%;
    -moz-transform-origin: 50% 25%;
    -ms-transform-origin: 50% 25%;
    transform-origin: 50% 25%;
}

.custom-dropdown, .carat {
    -webkit-transition: all 150ms ease-in-out;
    -moz-transition: all 150ms ease-in-out;
    -ms-transition: all 150ms ease-in-out;
    transition: all 150ms ease-in-out;
}

.custom-dropdown.open .carat {
    -webkit-transform: rotate(180deg);
    -moz-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg);    
}

.custom-dropdown ul {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    list-style: none;
    overflow: auto;
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    margin: 0;
    padding: 0;
}

.custom-dropdown li {
    list-style: none;
    padding: 14px 12px;
    border-top: 1px solid #ccc;
    font-size: 16px;
    font-weight: 400;
    text-transform: capitalize;
    line-height: 1;
    padding: 8px 12px;
    overflow: hidden;
    white-space: nowrap;
}

.custom-dropdown li.active {
    background: #EBEBEB;
}

.custom-dropdown li:hover {
    background: #EBEBEB;
}

#optionList {
    position: absolute;
    height: 0;
    left: 0;
    right: 0;
    top: 100%;
    background: #FFF;
    margin-top: 2px;
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    z-index: 100;
}

.openListHeight {
    border: 1px solid #A9A9A9;
    height: 259px !important;
}

</style>