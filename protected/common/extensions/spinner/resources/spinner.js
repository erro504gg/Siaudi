/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function getIndexOf(array, obj){
  for(var i=0; i<array.length; i++){
    if(array[i]==obj){
      return i;
    }
  }
  return -1;
}

var isMouseDown = false;

function setMouseDown(button,tf,type,list,minMax,step,prec, delay, acc){
  isMouseDown = true;
  doMouseDownEvent(button,tf,type,list,minMax,step,prec,delay,acc);
}

function setMouseUp(){
  isMouseDown = false;
}

function doMouseDownEvent(button,tf,type,list,minMax,step,prec,delay,acc){
  if (isMouseDown)  {
    var f;
    if(button == "down"){
      down(tf,type,list,minMax,step, prec,delay,acc)
    } else {
      up(tf,type,list,minMax,step, prec,delay,acc)
    }
  }
}

function up(tf,type,list,max,step, prec,delay,acc){
  var el = document.getElementById(tf);
  var value;
  if (isMouseDown)  {
    switch (type) {
      case "numeric" :
        value = parseFloat(el.value);
        if(value + step <= max){
          el.value = (value + step).toFixed(prec);
        } else {
          setMouseUp();
        }
        break;
      case "string" :
        el.value = getNextValue(list,el.value);
        break;
      case "time" :
        el.value = addMinutes(el.value, max,step);
        break;
      default:
        break;

    }
    f = function() {
      up(tf,type,list,max,step, prec,delay,acc);
    };
    if(delay > acc+1){
      delay-=acc;
    } else {
      delay =1
    }
    setTimeout(f,delay);
  }
}

function down(tf,type,list,min,step, prec,delay,acc){
  var el = document.getElementById(tf);
  var value;
  if (isMouseDown)  {
    switch (type) {
      case "numeric":
        value = parseFloat(el.value);
        if(value - step >= min){
          el.value= (value - step).toFixed(prec);
        } else {
          setMouseUp();
        }
        break;
      case "string" :
        el.value = getPreviousValue(list,el.value);
        break;
        case "time" :
        el.value = subMinutes(el.value, min,step);
        break;
      default:
        break;
    }
 
    f = function() { 
      down(tf,type,list,min,step, prec,delay,acc);
    };
    if(delay > acc+1){
      delay-=acc;
    }else {
      delay =1
    }
    setTimeout(f,delay);
  }
}

function getNextValue(list,value){
  var index = getIndexOf(list,value);
  if(index <0) {
    setMouseUp();
    return value;
  } else if (index < list.length-1){
    return list[index+1];
  } else {
    setMouseUp();
    return value;
  }
}

function getPreviousValue(list,value){
  var index = getIndexOf(list,value);
  if(index <0) {
    setMouseUp();
    return value;
  } else if (index > 0){
    return list[index-1];
  } else {
    setMouseUp();
    return value;
  }
}
 
  function addMinutes(curValue, maxValue, step) {
    maximums = maxValue.split(":", -1);
    maxMinutes = parseInt(maximums[0]*60) + parseInt(maximums[1]*1);
    minutes = curValue.split(":", -1);
    curMinutes =  parseInt(minutes[0]*60) + parseInt(minutes[1]*1) + parseInt(step*1);
    if(curMinutes <= maxMinutes) {
      return toHourFormat(curMinutes);
    } else {
      return curValue;
    }
  }

  function subMinutes(curValue, minValue, step) {
    minimums = minValue.split(":", -1);
    minMinutes = parseInt(minimums[0]*60) + parseInt(minimums[1]*1);
    minutes = curValue.split(":", -1);
    curMinutes = parseInt(minutes[0]*60) + parseInt(minutes[1]*1) - parseInt(step*1);
    if(curMinutes >= minMinutes) {
      return toHourFormat(curMinutes);
    } else {
      return curValue;
    }
  }

function toHourFormat(minutes) {
    hours = String(parseInt(minutes/60)).length == 2 ? parseInt(minutes/60) : "0"+parseInt(minutes/60);
    mins = String(minutes%60).length == 2 ? minutes%60 : "0" +(minutes%60);
    return hours+":"+mins;
}

