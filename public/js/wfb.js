/*
laukia kol useris nuskanuos barkoda*/
function formatedDate()
{
  var date = new Date();
  var month = (date.getMonth()+1).toString();
  var day = (date.getDate()).toString();
  var hours = (date.getHours()).toString();
  var minutes = (date.getMinutes()).toString();
  var seconds = (date.getSeconds()).toString();
  if(month.length < 2)
    month = "0"+month;
  if(day.length < 2)
    day = "0"+day;
  if(hours.length < 2)
   hours = "0"+hours;
  if(minutes.length <2)
    minutes = "0"+minutes;
  if(seconds.length <2)
    seconds = "0"+seconds;

  return date.getFullYear()+"-"+month+"-"+day+" "+hours+":"+minutes+":"+seconds;
}
var intervalID = null;
function setIntervalX(callback, delay, repetitions) {
    var x = 0;
    intervalID = window.setInterval(function () {

       callback();

       if (++x === repetitions) {
           noBarcodesFound();
           window.clearInterval(intervalID);
       }
    }, delay);
}

function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
