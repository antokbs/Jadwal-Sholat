function Body_Onload(Data) {
  if (Data == undefined) {
    ajax("", "ListSurah", "", function (cData) {
      if (cData !== "") {
        SetDataToTable(cData);
      } else {
        alert("Data Tidak Ditemukan, Mungkin Folder MP3 Salah, Periksa pada Configurasi");
      }
    });
  } else {
    SetDataToTable(Data);
  }
}

function SetDataToTable(cData) {
  var cTable = "";
  var n = "001";
  Data = JSON.parse(cData);
  Data.forEach((itemData) => {
    cTable += '<tr><td>' + itemData["nSurah"] + '</td>';
    cTable += '<td style="text-align:center;"><input type="checkbox" id="status_' + itemData["nSurah"] + '" name="cStatus_' + itemData["nSurah"] + '"><input type="hidden" name="cPath_' + itemData["nSurah"] + '" value="' + itemData["cPath"] + '"></td>';
    cTable += '<td id="surah_' + itemData["nSurah"] + '"><input type="hidden" name="cNamaSurah_' + itemData["nSurah"] + '" value="' + itemData["cSurah"] + '">' + itemData["cSurah"] + '</td>';
    cTable += '<td><input type="number" id="repeat_' + itemData["nSurah"] + '" name="nReapet_' + itemData["nSurah"] + '" value="1"></td>';
    cTable += '<td><input type="number" id="volum' + itemData["nSurah"] + '" name="nVolum_' + itemData["nSurah"] + '" value="1"></td>';
  });
  document.getElementById('bodytable').innerHTML = cTable;
}

function checkall(source) {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i] != source)
      checkboxes[i].checked = source.checked;
  }
}

