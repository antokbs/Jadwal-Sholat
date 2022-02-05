<?php
require_once __DIR__ . "/../../../include/system.php";

$nHijriah = GetConfig("nHijriah", 0);
$hijri = new HijriDate($nHijriah); //Wajib ada
$nTahun = isset($_POST["nTahun"]) ? $_POST["nTahun"] : date("Y");
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
<style type="text/css">
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

  .cellHari1 {
    background-color: rgb(252, 184, 184);
  }

  .cellHariIni {
    background-color: rgb(162, 225, 247);
  }

  .divH {
    color: darkgreen;
    font-weight: bolder;
    cursor: default;
    padding: 2px 0px 2px 0px;
  }

  .divH:hover {
    background-color: #dedede;
  }

  .divM {
    color: darkblue;
    font-weight: bolder;
    cursor: default;
    padding: 2px 0px 2px 0px;
  }
</style>

<body>
  <form name="form1" action="" method="POST">
    Tahun :
    <select name="nTahun" onchange="document.form1.submit();">
      <?php
      for ($n = 2020; $n <= date("Y") + 5; $n++) {
        $cSelected = "";
        if ($n == $nTahun) $cSelected = "selected";
        echo ("<option $cSelected value='$n'>$n</option>");
      }
      ?>
    </select>
  </form>
  <?php
  for ($nBulan = 1; $nBulan <= 12; $nBulan++) :
    $nEnd = date("d", mktime(0, 0, 0, $nBulan + 1, 0, $nTahun));
    $nRow = 0;
    $vaRow = [];
    $vaRow[$nRow] = [["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""]];
    $cHijriah = "";
    $cHijriahAwal = "";
    for ($nHari = 1; $nHari <= $nEnd; $nHari++) {
      $nTime = mktime(0, 0, 0, $nBulan, $nHari, $nTahun);
      $vaDate = getdate($nTime);

      $hijri->get_date($nTime, $nHijriah);
      if ($nHari == 1) {
        $cHijriahAwal = $hijri->get_month_name($hijri->get_month()) . " " . $hijri->get_year();
      }

      if ($hijri->get_day() == 1 && $cHijriah == "") {
        $cHijriah = $hijri->get_month_name($hijri->get_month()) . " " . $hijri->get_year();
      }
      $vaRow[$nRow][$vaDate["wday"]] = [$nHari, $hijri->get_day(), $hijri->get_day() . " " . $hijri->get_month_name($hijri->get_month()) . " " . $hijri->get_year()];

      if ($vaDate["wday"] == 6 && $nHari < $nEnd) {
        $vaRow[++$nRow] = [["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""]];
      }
    }
    if ($cHijriah == "") {
      $cHijriah = $cHijriahAwal;
    } else {
      $cHijriah = "$cHijriahAwal - $cHijriah";
    }
  ?>
    <table style="margin-top: 5px;">
      <tr>
        <td colspan="7" class="cellBulan"><?= $vaBulan[$nBulan - 1] . " " . $nTahun . "<br>$cHijriah" ?></td>
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
          <td class="cellHari <?= GetTglClass($cal[0], $nBulan, $nTahun) ?>">
            <div class="divM"><?= $cal[0][0] ?></div>
            <div class="divH" <?= GetTitle($cal[0]) ?>><?= $cal[0][1] ?></div>
          </td>
          <td class="cellHari <?= GetTglClass($cal[1], $nBulan, $nTahun) ?>">
            <div class="divM"><?= $cal[1][0] ?></div>
            <div class="divH" <?= GetTitle($cal[1]) ?>><?= $cal[1][1] ?></div>
          </td>
          <td class="cellHari <?= GetTglClass($cal[2], $nBulan, $nTahun) ?>">
            <div class="divM"><?= $cal[2][0] ?></div>
            <div class="divH" <?= GetTitle($cal[2]) ?>><?= $cal[2][1] ?></div>
          </td>
          <td class="cellHari <?= GetTglClass($cal[3], $nBulan, $nTahun) ?>">
            <div class="divM"><?= $cal[3][0] ?></div>
            <div class="divH" <?= GetTitle($cal[3]) ?>><?= $cal[3][1] ?></div>
          </td>
          <td class="cellHari <?= GetTglClass($cal[4], $nBulan, $nTahun) ?>">
            <div class="divM"><?= $cal[4][0] ?></div>
            <div class="divH" <?= GetTitle($cal[4]) ?>><?= $cal[4][1] ?></div>
          </td>
          <td class="cellHari <?= GetTglClass($cal[5], $nBulan, $nTahun) ?>">
            <div class="divM"><?= $cal[5][0] ?></div>
            <div class="divH" <?= GetTitle($cal[5]) ?>><?= $cal[5][1] ?></div>
          </td>
          <td class="cellHari <?= GetTglClass($cal[6], $nBulan, $nTahun) ?>">
            <div class="divM"><?= $cal[6][0] ?></div>
            <div class="divH" <?= GetTitle($cal[6]) ?>><?= $cal[6][1] ?></div>
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
<?php
function GetTglClass($cal, $nBulan, $nTahun)
{
  $cRetval = "";

  // Tanggal 1 Hijriah
  if ($cal[1] == 1) $cRetval = " cellHari1 ";

  // Kalau Hari ini
  if (date("Y-m-d", time()) == date("Y-m-d", mktime(0, 0, 0, $nBulan, $cal[0], $nTahun))) $cRetval = " cellHariIni ";
  return $cRetval;
}

function GetTitle($cal)
{
  return isset($cal[2]) ? " title=\"{$cal[2]}\" " : "";
}
?>

</html>