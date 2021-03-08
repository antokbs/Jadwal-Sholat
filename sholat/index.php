<?php
require_once "./include/system.php";
$url = GetURL();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Sholat</title>
  <link type='text/css' rel='stylesheet' href='<?= $url ?>css.css'>
  <script type="text/javascript" src="<?= $url ?>include/PrayTimes.js"></script>
  <script type="text/javascript" src="<?= $url ?>include/system.js"></script>
  <script type="text/javascript" src="<?= $url ?>index.js"></script>
</head>

<body onload="LoadForm('<?= $url ?>');" style="height:100%;margin:0px;overflow:hidden">
  <table class="mainTable" id="mainTable" cellspacing="1px" cellpadding="1px">
    <tr>
      <td colspan="3" id="cellTanggal" class="cellTanggal"> </td>
    </tr>
    <tr>
      <td colspan="3" id="cellJam" class="cellJam"> </td>
    </tr>
    <tr>
      <td class="cellWaktu" id="cellSubuh-Title">Subuh</td>
      <td class="cellWaktu" id="cellTerbit-Title">Terbit</td>
      <td class="cellWaktu" id="cellDzuhur-Title">Dzuhur</td>
    </tr>
    <tr>
      <td class="cellJadwal" id="cellSubuh">00:00</td>
      <td class="cellJadwal" id="cellTerbit">00:00</td>
      <td class="cellJadwal" id="cellDzuhur">00:00</td>
    </tr>
    <tr>
      <td class="cellWaktu" id="cellAshar-Title">Ashar</td>
      <td class="cellWaktu" id="cellMaghrib-Title">Maghrib</td>
      <td class="cellWaktu" id="cellIsya-Title">Isya</td>
    </tr>
    <tr>
      <td class="cellJadwal" id="cellAshar">00:00</td>
      <td class="cellJadwal" id="cellMaghrib">00:00</td>
      <td class="cellJadwal" id="cellIsya">00:00</td>
    </tr>
    <tr>
      <td colspan="3" class="cellSurat" id="cellSurat">&nbsp;</td>
    </tr>
  </table>
</body>

</html>