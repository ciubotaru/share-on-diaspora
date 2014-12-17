<?php
header("Content-type: text/css; charset: UTF-8");
include "../../../wp-load.php";
    $button_defaults = get_option('share-on-diaspora-settings');
    $options_array = get_option('share-on-diaspora-settings');
    $bc = !empty( $options_array['button_color'] ) ? $options_array['button_color'] : $button_defaults['button_color'];
    $bb = !empty( $options_array['button_background'] ) ? $options_array['button_background'] : $button_defaults['button_background'];
    $bc_h = !empty( $options_array['button_color_hover'] ) ? $options_array['button_color_hover'] : $button_defaults['button_color_hover'];
    $bb_h = !empty( $options_array['button_background_hover'] ) ? $options_array['button_background_hover'] : $button_defaults['button_background_hover'];

    $button_size = isset($options_array['button_size']) ? $options_array['button_size'] : $button_defaults['button_size'];
    switch ($button_size)
        {
        case '2': $bs = '28'; $fs = '17'; break;
        case '3': $bs = '33'; $fs = '20'; break;
        case '4': $bs = '48'; $fs = '29'; break;
        default: $bs = '23'; $fs = '14';  
        }

    $br = isset( $options_array['button_rounded'] ) ? $options_array['button_rounded'] : $button_defaults['button_rounded'];

    $css_content = "#diaspora-button-box {
    box-sizing: content-box;
    -moz-box-sizing: content-box;
    display: inline-block;
    height: " . $bs . "px;
    background-color: #" . $bb . ";
    -moz-border-radius: " . $br . "px;
    border-radius: " . $br . "px;
    border-color: #" . $bc . ";
    border-width: 1px;
    color: #" . $bc . ";
    border-style: solid;
    padding: 0 0 0 5px;
    text-align: center;
    font-size: ". $fs . "px;
    font-style: normal;
    font-weight: normal;
    line-height: 100%;}

#diaspora-button-box:hover {
    background-color: #" . $bb_h . ";
    border-color: #" . $bc_h . ";
    color: #" . $bc_h . "}  

#diaspora-button-box font {
    font-family: arial,helvetica,sans-serif;
    font-size: " . $fs ."px;
    margin: 0;
    line-height: " . ($bs-2) . "px;
    text-decoration: none} 

#diaspora-button-inner {
    float: right;
    margin: 1px 1px 1px 5px;
    height: " . ($bs-3) . "px;
    background-color: #" . $bb . "}

#diaspora-button-inner img {
    vertical-align: top;
    margin: 0 auto;
    padding: 0;
    border: 0}

#diaspora-button-ownimage-div {
    border: 0px none;
    padding: 0px}

#diaspora-button-ownimage-img {
    margin: 0px;
    padding: 0px;
    border: 0px none}

#diaspora-button-container {
    display: block}

#diaspora-button-podlist {
    background: #82A6B6;
    width: 268px;
    padding: 5px;
    font-size: 16px;
    line-height: 1;
    border: 0;
    border-radius: 0;
    height: 34px;
    -moz-appearance: none
    -webkit-appearance: none;
    color: #fff}
";
echo $css_content;
?>