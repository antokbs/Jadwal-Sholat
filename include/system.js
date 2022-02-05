var BASE_URL = "";
var vaConfig = {};

function initConfig(callBack) {
  ajax("", "CheckConfig", "", function (cData) {
    var va = JSON.parse(cData);
    for (var k in va) {
      SaveCfg(k, va[k]);
    }

    callBack();
  });
}

function _id(cID) {
  return document.getElementById(cID);
}

/*
* Function Untuk Mengambil Tanggal sekarang Jangan pakai function New Date() 
* Karena dengan function ini kita akan mengambil Timezone di configurasi
* Dan Bukan Mengambile Timezone di komputer
*/
function MyDate() {
  var d = new Date();

  /* 
  * Timezone sesuai yang di Setting di config Jadiman Menit  
  * Kita tambah dengan Timezone yang ada di Komputer
  * Ini supaya kita bisa setting timezone tanpa harus merubah timezone komputer
  */
  var nTimeZone = Math.floor(GetCfg("nTimeZone", 7)) * 60;
  nTimeZone += d.getTimezoneOffset();

  d.setMinutes(d.getMinutes() + nTimeZone);
  return d;
}

function GetCfg(key, cDefault = "") {
  if (key in vaConfig) {
    cDefault = vaConfig[key];
  }
  return cDefault;
}

function SaveCfg(key, value) {
  vaConfig[key] = value;
}

function addZero(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}

function ajax(url, cKey, cParameter, callBack) {
  var page = false;
  cMethod = "POST";
  if (window.XMLHttpRequest) { // if Mozilla, Safari etc
    page = new XMLHttpRequest();
  } else if (window.ActiveXObject) { // if IE
    try {
      page = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        page = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) { }
    }
  } else {
    return false
  }

  page.onreadystatechange = function () {
    if (page !== null) {
      try {
        if (page.readyState == 4) {
          if (page.status == 200) {
            cRetval = page.responseText;
            if (callBack) {
              callBack(cRetval.trim(), page.status);
            } else {
              eval(cRetval);
            }
          }
        }
      } catch (e) {
        if (e.message.indexOf('NS_ERROR_NOT_AVAILABLE') < 0) {
          cRetval = page.responseText;
          if (callBack) {
            callBack(cRetval.trim(), page.status);
          } else {
            eval(cRetval);
          }
        }
      }
    }
  };

  //url += "&cKey=" + cKey;
  //url += "ajax.php?__par=" + url;

  url += "ajax.php?__par=" + url + "&cKey=" + cKey;
  if (typeof BASE_URL !== "undefined") url = BASE_URL + url;

  if (!cParameter) cParameter = "";
  page.open(cMethod, url, true);
  page.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  page.send(cParameter);
}

function Time2Menit(cTime) {
  var va = cTime.split(":");
  var nMenit = (va[0]) * 60;

  if (va.length >= 2) nMenit += Math.floor(va[1]);
  return nMenit;
}

function Menit2Time(nMenit) {
  var nJam = Math.floor(nMenit / 60);
  nMenit -= (nJam * 60);
  return addZero(nJam) + ":" + addZero(nMenit);
}