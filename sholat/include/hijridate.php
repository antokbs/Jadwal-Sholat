<?php
class HijriDate
{

  private $hijri;

  public function __construct($nDay = 0)
  {
    $this->hijri = $this->GregorianToHijri($nDay);
  }

  public function get_date()
  {
    return $this->hijri[1] . ' ' . $this->get_month_name($this->hijri[0]) . ' ' . $this->hijri[2] . 'H';
  }

  public function get_day()
  {
    return $this->hijri[1];
  }

  public function get_month()
  {
    return $this->hijri[0];
  }

  public function get_year()
  {
    return $this->hijri[2];
  }

  public function get_month_name($i)
  {
    static $month  = array(
      "Muharram",
      "Safar",
      "Rabiul Awal",
      "Rabiul Akhir",
      "Jumadil Awal",
      "Jumadil Akhir",
      "Rajab",
      "Sya'ban",
      "Ramadhan",
      "Syawal",
      "Dzulkaidah",
      "Dzulhijjah"
    );
    return $month[$i - 1];
  }

  private function GregorianToHijri($nDay)
  {
    $nDay *= 86400;
    $time = time() + $nDay;
    $m = date('m', $time);
    $d = date('d', $time);
    $y = date('Y', $time);

    return $this->JDToHijri(cal_to_jd(CAL_GREGORIAN, $m, $d, $y));
  }

  # Julian Day Count To Hijri
  private function JDToHijri($jd)
  {
    $jd = $jd - 1948440 + 10632;
    $n  = (int)(($jd - 1) / 10631);
    $jd = $jd - 10631 * $n + 354;
    $j  = ((int)((10985 - $jd) / 5316)) *
      ((int)(50 * $jd / 17719)) +
      ((int)($jd / 5670)) *
      ((int)(43 * $jd / 15238));
    $jd = $jd - ((int)((30 - $j) / 15)) *
      ((int)((17719 * $j) / 50)) -
      ((int)($j / 16)) *
      ((int)((15238 * $j) / 43)) + 29;
    $m  = (int)(24 * $jd / 709);
    $d  = $jd - (int)(709 * $m / 24);
    $y  = 30 * $n + $j - 30;

    return array($m, $d, $y);
  }
}
