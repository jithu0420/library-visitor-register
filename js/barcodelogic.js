document.getElementById('admission_no').focus();

let check_in_out = document.getElementById('check_in_out');
check_in_out.addEventListener('click', function () {
    var admission_no = document.getElementById('admission_no').value;
    fetchStudent(admission_no);
});

const myform = document.getElementById('myform');
myform.addEventListener('submit', function (event) {
    event.preventDefault();
    var admission_no = document.getElementById('admission_no').value;
    fetchStudent(admission_no);
});

function fetchStudent(admission_no) {
    const currentTime = new Date().toLocaleTimeString('en-GB', { hour: "numeric", minute: "numeric" });
    const currentDate = new Date().toLocaleDateString('en-GB');  //en-GB British English format

    if (admission_no.length == 0) {
        document.getElementById('name').innerHTML = "ID not scanned properly !";
        return;
    }
    else {
        document.getElementById('admission_no').value = "";

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {

                const myObj = JSON.parse(this.responseText);
                
                if (myObj.name != undefined) {
                    document.getElementById('name').innerHTML = myObj.name;
                    setTimeout(function () { document.getElementById('name').innerHTML = ""; }, 5000);
                }
                else {
                    document.getElementById('name').innerHTML = "";
                }
                if (myObj.entry_time != undefined) {
                    document.getElementById('entry_time').innerHTML = myObj.entry_time;
                    setTimeout(function () { document.getElementById('entry_time').innerHTML = ""; }, 5000);
                }
                else {
                    document.getElementById('entry_time').innerHTML = "";
                }
                if (myObj.exit_time != undefined) {
                    document.getElementById('exit_time').innerHTML = myObj.exit_time;
                    setTimeout(function () { document.getElementById('exit_time').innerHTML = ""; }, 5000);
                }
                else {
                    document.getElementById('exit_time').innerHTML = "";
                }
                if (myObj.error != undefined) {
                    document.getElementById('error_info').innerHTML = myObj.error;
                    setTimeout(function () { document.getElementById('error_info').innerHTML = ""; }, 5000);
                }
                else {
                    document.getElementById('error_info').innerHTML = "";
                }
            }
        };
        xmlhttp.open("POST", "php/fetchstudent.php?admno=" + admission_no + "&time=" + currentTime + "&date=" + currentDate, true);
        xmlhttp.send();
    }
}