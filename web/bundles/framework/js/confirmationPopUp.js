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
        dialogbox.style.bottom = (winH/2)+"px";
        dialogbox.style.display = "block";
        document.getElementById('dialogboxhead').innerHTML = "Message";
        document.getElementById('dialogboxbody').innerHTML = dialog;
        document.getElementById('dialogboxfoot').innerHTML = '<button class="btn" onclick="Alert.ok()">OK</button>';
    }

    this.render = function(dialog, msgType){
        var winW = window.innerWidth;
        var winH = window.innerHeight;
        var dialogoverlay = document.getElementById('dialogoverlay');
        var dialogbox = document.getElementById('dialogbox');
        dialogoverlay.style.display = "block";
        dialogoverlay.style.height = winH+"px";
        dialogbox.style.left = (winW/2) - (550 * .5)+"px";
        dialogbox.style.bottom = (winH/2)+"px";
        dialogbox.style.display = "block";
        document.getElementById('dialogboxhead').innerHTML = msgType;
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
        dialogbox.style.bottom = (winH/2)+"px";
        dialogbox.style.display = "block";

        document.getElementById('dialogboxhead').innerHTML = "Confirm Remove Action";
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
        if (document.readyState === "interactive") {
            document.getElementById('dialogbox').style.display = "none";
            document.getElementById('dialogoverlay').style.display = "none";
        }
    }
}
var Confirm = new CustomConfirm();


function CustomInput(){

    this.render = function(dialog,op,path,oldValue){
        var winW = window.innerWidth;
        var winH = window.innerHeight;
        var dialogoverlay = document.getElementById('dialogoverlay');
        var dialogbox = document.getElementById('dialogbox');
        dialogoverlay.style.display = "block";
        dialogoverlay.style.height = winH+"px";
        dialogbox.style.left = (winW/2) - (550 * .5)+"px";
        dialogbox.style.bottom = (winH/2)+"px";
        dialogbox.style.display = "block";

        document.getElementById('dialogboxhead').innerHTML = "Change Response interval";
        document.getElementById('dialogboxbody').innerHTML = '<form>'+dialog +' : <input type="number" id="range" name="range" min="1" max="1000" step="1" > s</form>';
        document.getElementById('dialogboxfoot').innerHTML = '<button onclick="Input.yes(\''+op+'\',\''+path+'\')" class="btn">OK</button> <button class="btn" onclick="Input.no()">Cancel</button>';

        document.getElementById('range').value=oldValue;

    }
    this.no = function(){
        Input.removeError();
        document.getElementById('dialogbox').style.display = "none";
        document.getElementById('dialogoverlay').style.display = "none";
    }
    this.yes = function(op,path) {

        if (op == "input") {
            var value = document.getElementById('range').value;
            if (value != null){

                if (isNaN(value)) {
                    Input.setError("Value is not valid number");
                }
                else if (parseInt(Number(value)) == value && !isNaN(parseInt(value, 10))) {
                    if (parseInt(Number(value)) > 0){

                        Input.removeError();
                        window.location = path + "&value=" + value;

                        if (document.readyState === "interactive") {
                            document.getElementById('dialogbox').style.display = "none";
                            document.getElementById('dialogoverlay').style.display = "none";
                        }

                    }else{
                        Input.setError("Time should be greater than zero");
                    }

                }
                else {
                    Input.setError("Value is not integer");
                }
            }else{
                Input.setError("Please enter a value")
            }

        }
        else {
            Input.removeError();
            document.getElementById('dialogbox').style.display = "none";
            document.getElementById('dialogoverlay').style.display = "none";
        }
    }

    this.initiateError = function(){
        document.getElementById('inputError').classList.add('inputError');
    }

    this.setError = function(error){
        Input.initiateError();
        document.getElementById('inputError').classList.add('alert');
        document.getElementById('inputError').classList.add('alert-danger');
        document.getElementById('inputError').innerHTML = '<span class="glyphicon glyphicon-remove"></span>' + error;
    }

    this.removeError = function(){
        document.getElementById('inputError').classList.remove('inputError');
        document.getElementById('inputError').classList.remove('alert');
        document.getElementById('inputError').classList.remove('alert-danger');
        document.getElementById('inputError').innerHTML = "";
    }
}
var Input = new CustomInput();

