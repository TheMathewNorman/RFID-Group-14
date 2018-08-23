function sanitizeInput(str){
    sanInput = str.trim(str.toLowerCase());
    sanInput.replace(/<[^>]*>/g, '');
    return sanInput;
}

function validateInput() {
    var input = sanitizeInput(document.forms["search"]["searchInput"].value);
    if(input != /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@*((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/){
        if(input != "")
            document.forms["search"]["searchInput"].value = input  
    }
}