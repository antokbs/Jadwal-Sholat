var BASE_URL = "";
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

  url += "&cKey=" + cKey;
  url = "ajax.php?__par=" + url;
  if (typeof BASE_URL !== "undefined") url = BASE_URL + url;

  if (!cParameter) cParameter = "";
  page.open(cMethod, url, true);
  page.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  page.send(cParameter);
}