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
  * Timezone sesuai yang di Setting di config Jadikan Menit  
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

  url += "ajax.php?__par=" + url + "&cKey=" + cKey;
  if (typeof BASE_URL !== "undefined") url = BASE_URL + url;

  var href = window.location.href;
  var last = href.charAt(href.length - 1);
  if (last !== "/") {
    url = window.location.href + "/" + url;
  }

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

function GetFormContent(elem = null) {
  var sXml = "";
  var frm = document.forms[0];
  var el = null;
  if (elem !== null) {
    if (elem.tagName && elem.tagName == 'FORM') {
      frm = elem;
    } else {
      el = elem;
    }
  }
  if (frm && frm.tagName == 'FORM') {
    if (el == null) el = frm.elements;
    for (var i = 0; i < el.length; i++) {
      if (!el[i].name)
        continue;
      if (el[i].type && (el[i].type == 'radio' || el[i].type == 'checkbox') && el[i].checked == false)
        continue;
      if (el[i].disabled && el[i].disabled == true)
        continue;

      var name = el[i].name;
      if (name) {
        if (sXml != '') {
          sXml += '&';
        }
        if (el[i].type == 'select-multiple') {
          for (var j = 0; j < el[i].length; j++) {
            if (el[i].options[j].selected == true) {
              sXml += name + "=" + encodeURIComponent(el[i].options[j].value) + "&";
            }
          }
        } else {
          sXml += name + "=" + encodeURIComponent(el[i].value);
        }
      }
    }
  }
  return sXml;
}