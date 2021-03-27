<?php
require_once "../include/system.php";
$url = GetURL();


$cKoordinat = GetConfig("cKoordinat", "0,0");
$nTimezone = GetConfig("nTimeZone", 7);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script type="text/javascript" src="<?= $url ?>../include/PrayTimes.js"></script>
  <style type="text/css">
    .rowHeader td {
      border: 1px solid #aaaaaa;
      text-align: center;
      padding: 4px;
      background-color: #aaaaaa;
      white-space: nowrap;
    }

    .rowData td {
      border: 1px solid #aaaaaa;
      text-align: center;
      padding: 4px;
      background-color: #dedede;
      white-space: nowrap;
    }

    .rowToday td {
      background-color: yellow;
    }

    .tabMain {
      margin-left: auto;
      margin-right: auto;
    }
  </style>
  <script>
    function LoadForm() {
      BuildCalendar();
    }

    function initTable(table) {
      while (table.rows.length > 2) {
        table.deleteRow(2);
      }
    }

    function BuildCalendar() {
      var vaKoordinat = [<?= $cKoordinat ?>];
      var nTimezone = <?= $nTimezone ?>;
      var table = document.getElementById("tbCalendar");
      var nBulan = Math.floor(document.getElementById("nBulan").value) + 1;
      var nTahun = document.getElementById("nTahun").value;
      var d = new Date(nTahun, nBulan, 0);
      var now = new Date();

      initTable(table);

      prayTimes.setMethod("INDONESIA");
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
        row.insertCell().innerHTML = times.fajr;
        row.insertCell().innerHTML = times.sunrise;
        row.insertCell().innerHTML = times.dhuhr;
        row.insertCell().innerHTML = times.asr;
        row.insertCell().innerHTML = times.maghrib;
        row.insertCell().innerHTML = times.isha;
      }
      //console.log(d.getDate());
    }

    function addZero(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    }
  </script>
  <title>Document</title>
</head>

<body onload="LoadForm()" style="text-align: center;">
  <table id="tbCalendar" class="tabMain">
    <tr>
      <td colspan="8">
        Bulan :
        <select name="nBulan" id="nBulan" onChange="BuildCalendar()">
          <?php
          $vaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
          foreach ($vaBulan as $key => $value) {
            $c = "";
            if ($key + 1 == date("m")) $c = "selected";
            echo ("<option $c value='$key'>$value</option>");
          }
          ?>
        </select>
        <select name="nTahun" id="nTahun" onChange="BuildCalendar()">
          <?php
          for ($n = date("Y") - 5; $n <= date("Y") + 5; $n++) {
            $c = "";
            if ($n == date("Y")) $c = "selected";
            echo ("<option value='$n' $c>$n</option>");
          }
          ?>
        </select>
      </td>
    </tr>
    <tr class="rowHeader">
      <td>No</td>
      <td>Tgl</td>
      <td>Subuh</td>
      <td>Terbit</td>
      <td>Dzuhur</td>
      <td>Ashar</td>
      <td>Maghrib</td>
      <td>Isya</td>
    </tr>
  </table>
</body>

</html>