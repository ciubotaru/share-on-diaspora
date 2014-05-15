function addCheckbox() {
    var newpodname = document.getElementsByName("newpodname")[0].value;
    var myCurrentTable = document.getElementsByName("custom-pods")[0];
    var txt = myCurrentTable.innerHTML;
    txt = txt + "<tr><th scope='row'>" + newpodname + "</th><td><input type='checkbox' name='share-on-diaspora-settings[podlist][" + newpodname + "]' value='1' checked=true/></td></tr>";
    myCurrentTable.innerHTML = txt;
    document.getElementsByName("newpodname")[0].value = '';
    };
