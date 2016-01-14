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