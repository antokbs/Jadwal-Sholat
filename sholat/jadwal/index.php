<?php
require_once __DIR__ . "/../../include/system.php";
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
  <script type="text/javascript" src="<?= $url ?>../include/system.js"></script>
  <script type="text/javascript" src="<?= $url ?>../include/PrayTimes.js"></script>
  <script type="text/javascript" src="<?= $url ?>index.js"></script>
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
  <title>Jadwal Sholat Bulanan</title>
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