<?php
require_once "../include/system.php";

$nHijriah = GetConfig("nHijriah", 0);
$hijri = new HijriDate($nHijriah); //Wajib ada
$nTahun = 2021;
$vaBulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar</title>
</head>
<style>
  .cellBulan {
    border: 1px solid #aaaaaa;
    text-align: center;
    padding: 4px;
    background-color: #aaaaaa;
  }

  .cellMinggu {
    border: 1px solid #aaaaaa;
    text-align: center;
    padding: 4px;
    background-color: #dedede;
  }

  .cellHari {
    border: 1px solid #aaaaaa;
    text-align: center;
    width: 60px;
  }

  .divH {
    color: darkgreen;
    font-weight: bolder;
  }

  .divM {
    color: darkblue;
    font-weight: bolder;
  }
</style>

<body>
  <?php
  for ($nBulan = 1; $nBulan <= 12; $nBulan++) :
    $nEnd = date("d", mktime(0, 0, 0, $nBulan + 1, 0, $nTahun));
    $nRow = 0;
    $vaRow = [];
    $vaRow[$nRow] = [["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""]];
    $cHijriah = "";
    for ($nHari = 1; $nHari <= $nEnd; $nHari++) {
      $nTime = mktime(0, 0, 0, $nBulan, $nHari, $nTahun);
      $vaDate = getdate($nTime);

      $hijri->get_date($nTime, $nHijriah);
      if ($hijri->get_day() == 1 && $cHijriah == "") {
        $cHijriah = $hijri->get_month_name($hijri->get_month()) . " " . $hijri->get_year();
      }
      $vaRow[$nRow][$vaDate["wday"]] = [$nHari, $hijri->get_day()];

      if ($vaDate["wday"] == 6 && $nHari < $nEnd) {
        $vaRow[++$nRow] = [["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""]];
      }
    }
    if ($cHijriah == "") {
      $cHijriah = $hijri->get_month_name($hijri->get_month()) . " " . $hijri->get_year();
    }
  ?>
    <table style="margin-top: 5px;">
      <tr>
        <td colspan="7" class="cellBulan"><?= $vaBulan[$nBulan - 1] . " " . $nTahun . " - $cHijriah" ?></td>
      </tr>
      <tr>
        <td class="cellMinggu">Ahad</td>
        <td class="cellMinggu">Senin</td>
        <td class="cellMinggu">Selasa</td>
        <td class="cellMinggu">Rabu</td>
        <td class="cellMinggu">Kamis</td>
        <td class="cellMinggu">Jum'at</td>
        <td class="cellMinggu">Sabtu</td>
      </tr>
      <?php
      foreach ($vaRow as $cal) :
      ?>
        <tr>
          <td class="cellHari">
            <div class="divM"><?= $cal[0][0] ?></div>
            <div class="divH"><?= $cal[0][1] ?></div>
          </td>
          <td class="cellHari">
            <div class="divM"><?= $cal[1][0] ?></div>
            <div class="divH"><?= $cal[1][1] ?></div>
          </td>
          <td class="cellHari">
            <div class="divM"><?= $cal[2][0] ?></div>
            <div class="divH"><?= $cal[2][1] ?></div>
          </td>
          <td class="cellHari">
            <div class="divM"><?= $cal[3][0] ?></div>
            <div class="divH"><?= $cal[3][1] ?></div>
          </td>
          <td class="cellHari">
            <div class="divM"><?= $cal[4][0] ?></div>
            <div class="divH"><?= $cal[4][1] ?></div>
          </td>
          <td class="cellHari">
            <div class="divM"><?= $cal[5][0] ?></div>
            <div class="divH"><?= $cal[5][1] ?></div>
          </td>
          <td class="cellHari">
            <div class="divM"><?= $cal[6][0] ?></div>
            <div class="divH"><?= $cal[6][1] ?></div>
          </td>
        </tr>
      <?php
      endforeach;
      ?>
    </table>
  <?php
  endfor;
  ?>
</body>

</html>