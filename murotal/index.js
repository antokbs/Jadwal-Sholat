function checkall(source) {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].id.substring(0, source.id.length) == source.id && checkboxes[i] != source)
      checkboxes[i].checked = source.checked;
  }
  CheckAutoVolume();
}

function CheckAutoVolume() {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].id.substring(0, 8) == "vol_auto") {
      ClickVolAuto(checkboxes[i]);
    }
  }
}

function ClickVolAuto(field) {
  let name = "volume" + field.id.substring(8);
  let i = document.getElementById(name);

  if (i !== null) {
    i.readOnly = field.checked;
    i.style.backgroundColor = field.checked ? "#dedede" : "";
    i.value = field.checked ? document.form1.nMurotal_Volume.value : i.value;
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
      nCheck2 = getField("status2_" + id, 0);
      nRepeat2 = getField("repeat2_" + id, 1);
      nVolume = getField("volume_" + id, 60);
      nVol_Auto = getField("vol_auto_" + id, 0);
      cPath = getField("cPath_" + id, "");

      c += "&r_" + id + "=" + cPath + "," + nCheck + "," + nRepeat + "," + nVolume + "," + nCheck2 + "," + nRepeat2 + "," + nVol_Auto;
    }
  }
  if (confirm("Data Disimpan ?")) {
    ajax("", "SaveData", c, function (cData) {
      alert(cData);
    });
  }
}