/**
 * Created by Nadheesh on 1/13/2016.
 */


function confirmation(msg , path ) {

    if (confirm(msg) == true) {
        window.location=path;
    } else {

    }

}


function infoPopUp(msg){
    alert(msg);
}




///////////////////////////////////////////////////////////////////////////

function CustomAlert(){

    this.render = function(dialog){
        var winW = window.innerWidth;
        var winH = window.innerHeight;
        var dialogoverlay = document.getElementById('dialogoverlay');
        var dialogbox = document.getElementById('dialogbox');
        dialogoverlay.style.display = "block";
        dialogoverlay.style.height = winH+"px";
        dialogbox.style.left = (winW/2) - (550 * .5)+"px";
        dialogbox.style.top = "100px";
        dialogbox.style.display = "block";
        document.getElementById('dialogboxhead').innerHTML = "Acknowledge This Message";
        document.getElementById('dialogboxbody').innerHTML = dialog;
        document.getElementById('dialogboxfoot').innerHTML = '<button class="btn" onclick="Alert.ok()">OK</button>';
    }
    this.ok = function(){
        document.getElementById('dialogbox').style.display = "none";
        document.getElementById('dialogoverlay').style.display = "none";
    }
}

var Alert = new CustomAlert();

//function deleteRow(id){
//    //var db_id = id.replace("post_", "");
//    //// Run Ajax request here to delete post from database
//    //document.body.removeChild(document.getElementById(id));
//}


function CustomConfirm(){
    this.render = function(dialog,op,path){
        var winW = window.innerWidth;
        var winH = window.innerHeight;
        var dialogoverlay = document.getElementById('dialogoverlay');
        var dialogbox = document.getElementById('dialogbox');
        dialogoverlay.style.display = "block";
        dialogoverlay.style.height = winH+"px";
        dialogbox.style.left = (winW/2) - (550 * .5)+"px";
        dialogbox.style.top = "100px";
        dialogbox.style.display = "block";

        document.getElementById('dialogboxhead').innerHTML = "Confirm remove action";
        document.getElementById('dialogboxbody').innerHTML = dialog;
        document.getElementById('dialogboxfoot').innerHTML = '<button onclick="Confirm.yes(\''+op+'\',\''+path+'\')" class="btn">Yes</button> <button class="btn" onclick="Confirm.no()">No</button>';
    }
    this.no = function(){
        document.getElementById('dialogbox').style.display = "none";
        document.getElementById('dialogoverlay').style.display = "none";
    }
    this.yes = function(op,path){
        if(op == "delete_row"){
            window.location=path;
        }
        document.getElementById('dialogbox').style.display = "none";
        document.getElementById('dialogoverlay').style.display = "none";
    }
}
var Confirm = new CustomConfirm();

