function checkall(source) {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i] != source)
      checkboxes[i].checked = source.checked;
  }
}

function setupDisplay(n) {
  let divsToHide = document.getElementsByClassName("qari_" + n); //divsToHide is an array
  for (var i = 0; i < divsToHide.length; i++) {
    if (divsToHide[i].style.display == "none") {
      divsToHide[i].style.display = "";
    } else {
      divsToHide[i].style.display = "none";
    }
  }
}

function getField(id, value = "") {
  let obj = document.getElementById(id);
  if (obj !== null) {
    if (obj.type == "checkbox") {
      value = obj.checked ? 1 : 0;
    } else {
      value = obj.value;
    }
  }
  return value;
}

function cmdSave_onClick() {
  var t = document.getElementById('bodytable');
  var c = "";
  let id = "";

  let nCheck = 0;
  let nRepeat = 0;
  let nVolume = 0;
  let cPath = "";
  for (var row = 0; row < t.rows.length; row++) {
    id = t.rows[row].cells[0].id;
    if (id != "") {
      nCheck = getField("status_" + id, 0);
      nRepeat = getField("repeat_" + id, 1);
      nVolume = getField("volume_" + id, 60);
      cPath = getField("cPath_" + id, "");

      c += "&r_" + id + "=" + cPath + "," + nCheck + "," + nRepeat + "," + nVolume;
    }
  }
  if (confirm("Data Disimpan ?")) {
    ajax("", "SaveData", c, function (cData) {
      alert(cData);
    });
  }
}