function radioChecked (gridRadios) {

    let checkboxes = document.getElementsByName(gridRadios),
        i = checkboxes.length - 1;

    for ( ; i > -1 ; i-- ) {

        if ( checkboxes[i].checked ) { return true; }

    }

    return false;
}

$("input:radio[name=gridRadios]").click(function() {
    let radioArr = $("input:radio[name=gridRadios]");
    let hint1 = document.getElementById("keyInput1");
    let key2 = document.getElementById("key2");
    for(let i = 0; i < radioArr.length; i++) {
        let radioButton = radioArr[i];
        if(radioButton.checked) {
            switch(radioButton.value) {
                case "option1":
                    hint1.placeholder = "Ex. UEOQDGYSXCFPHWVJRTNIAMBZKL";
                    key2.hidden = true;
                    break;
                case "option2":
                    hint1.placeholder = "Ex. 7,2,0,5,4,1,6,3";
                    key2.hidden = false;
                    break;
                case "option3":
                    hint1.placeholder = "Ex. 2ewxq32%";
                    key2.hidden = true;
                    break;
            }
            break;
        }
    }
});