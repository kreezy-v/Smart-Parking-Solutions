var displayThis = document.getElementById("displayThis");
var textInside = document.getElementById("text-inside");
var replyBox = document.getElementById("replyBox");
replyBox.style.display = "none";
displayThis.style.display = "none";

function ref(){
    location.reload();
}
function replyFunc(a){
    replyBox.style.display = "none";
    displayThis.style.display = "block";
    setTimeout(() => {
        displayThis.style.display = "none";
        replyBox.style.display = "block";
        textInside.innerText = "";
        if(a == "rfid"){
            textInside.innerText = "First you need to be registered with the Admin Department, then scan your RFID card through the scanner.";
        }
        else if(a == "sps"){
            textInside.innerText = "Students, Faculty Members and Teachers";
        
        }
        else if(a == "reg"){
            textInside.innerText = "Register your RFID from Admin Department";
        }
    }, 900);
}

