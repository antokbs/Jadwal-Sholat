function LoadForm() {
  initConfig(BuildCalendar);
}

function initTable(table) {
  while (table.rows.length > 2) {
    table.deleteRow(2);
  }
}

function BuildCalendar() {
  var table = _id("tbCalendar");
  var nBulan = Math.floor(_id("nBulan").value) + 1;
  var nTahun = _id("nTahun").value;
  var d = new Date(nTahun, nBulan, 0);
  var now = new Date();

  initTable(table);

  var nTimezone = GetCfg("nTimezone", 7);

  prayTimes.setMethod("INDONESIA");
  var vaKoordinat = GetCfg("cKoordinat", "0,0").split(",");
  if (vaKoordinat.length < 2) vaKoordinat[1] = 0;
  vaKoordinat[0] = parseFloat(vaKoordinat[0]);
  vaKoordinat[1] = parseFloat(vaKoordinat[1]);

  for (var n = 1; n <= d.getDate(); n++) {
    var d1 = new Date(d.getFullYear(), d.getMonth(), n);
    var times = prayTimes.getTimes(d1, vaKoordinat, nTimezone);

    var row = table.insertRow();
    row.className = "rowData";
    if (d1.getFullYear() == now.getFullYear() && d1.getMonth() == now.getMonth() && d1.getDate() == now.getDate()) {
      row.className += " rowToday";
    }

    row.insertCell().innerHTML = n;
    row.insertCell().innerHTML = addZero(d1.getDate()) + "-" + addZero(d1.getMonth() + 1) + "-" + d1.getFullYear();
    row.insertCell().innerHTML = Menit2Time(Time2Menit(times.fajr) + 2 + Math.floor(GetCfg("nSubuh", 0)));
    row.insertCell().innerHTML = Menit2Time(Time2Menit(times.sunrise) + 2);
    row.insertCell().innerHTML = Menit2Time(Time2Menit(times.dhuhr) + 2 + Math.floor(GetCfg("nDzuhur", 0)));
    row.insertCell().innerHTML = Menit2Time(Time2Menit(times.asr) + 2 + Math.floor(GetCfg("nAshar", 0)));
    row.insertCell().innerHTML = Menit2Time(Time2Menit(times.maghrib) + 2 + Math.floor(GetCfg("nMaghrib", 0)));
    row.insertCell().innerHTML = Menit2Time(Time2Menit(times.isha) + 2 + Math.floor(GetCfg("nIsya", 0)));
  }
  //console.log(d.getDate());
}