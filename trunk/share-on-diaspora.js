function addCheckbox() {
	var newpodname = document.getElementsByName( "newpodname" )[0].value;
	if (newpodname == '') { return; }
	var newpodtr = document.createElement( "tr" );
	newpodtr.innerHTML = "<th scope='row'>" + newpodname + "</th><td><input type='checkbox' name='share-on-diaspora-settings[podlist][" + newpodname + "]' value='1' checked=true/></td>";
	var rows = document.getElementsByTagName( "tr" );
	var lastrow = rows[rows.length - 1];
	lastrow.parentNode.insertBefore( newpodtr,lastrow );
	document.getElementsByName( "newpodname" )[0].value = '';
};

function updateColorProfile(bg, bg_hover, text, text_hover) {
	document.getElementsByName( "share-on-diaspora-settings[button_background]" )[0].value = bg;
	document.getElementsByName( "share-on-diaspora-settings[button_background_hover]" )[0].value = bg_hover;
	document.getElementsByName( "share-on-diaspora-settings[button_color]" )[0].value = text;
	document.getElementsByName( "share-on-diaspora-settings[button_color_hover]" )[0].value = text_hover;
};
