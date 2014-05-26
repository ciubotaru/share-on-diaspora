function addCheckbox() {
    var newpodname = document.getElementsByName("newpodname")[0].value;
    if (newpodname == '') return;
    var newpodtr = document.createElement("tr");
    newpodtr.innerHTML = "<th scope='row'>" + newpodname + "</th><td><input type='checkbox' name='share-on-diaspora-settings[podlist][" + newpodname + "]' value='1' checked=true/></td>";
    var rows = document.getElementsByTagName("tr");
    var lastrow = rows[rows.length - 1];
    lastrow.parentNode.insertBefore(newpodtr,lastrow);
    document.getElementsByName("newpodname")[0].value = '';
    };