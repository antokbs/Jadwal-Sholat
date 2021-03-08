var nCheckJadwal = -1;
var vaJadwal = {
  "subuh": { "adzan": 0, "iqomah": 0 },
  "terbit": { "adzan": 0, "iqomah": 10 },
  "dzuhur": { "adzan": 0, "iqomah": 0 },
  "ashar": { "adzan": 0, "iqomah": 0 },
  "maghrib": { "adzan": 0, "iqomah": 0 },
  "isya": { "adzan": 0, "iqomah": 0 }
};
var vaConfig = {};
var cellJam = null;
var vaIqomah = { "start": -1, "end": -1, "sholat": "" };
var nLamaAdzan = 3; // Untuk Waktu Adzan Kita toleransi 3 Menit
function LoadForm(url) {
  BASE_URL = url;
  showTime();
}

function Time2Menit(cTime) {
  var va = cTime.split(":");
  var nMenit = (va[0]) * 60;

  if (va.length >= 2) nMenit += Math.floor(va[1]);
  return nMenit;
}

function Menit2Time(nMenit) {
  var nJam = Math.floor(nMenit / 60);
  nMenit -= (nJam * 60);
  return addZero(nJam) + ":" + addZero(nMenit);
}

function GetCfg(key, cDefault = "") {
  if (key in vaConfig) {
    cDefault = vaConfig[key];
  }
  return cDefault;
}

function SaveCfg(key, value) {
  vaConfig[key] = value;
}

function jadwalSholat() {
  var date = new Date();
  var vaHari = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
  var vaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  var c = vaHari[date.getDay()] + ", " + addZero(date.getDate()) + " " + vaBulan[date.getMonth()] + " " + date.getFullYear().toString();
  _id("cellTanggal").innerText = c;

  prayTimes.setMethod("INDONESIA");
  var vaKoordinat = GetCfg("cKoordinat", "0,0").split(",");

  if (vaKoordinat.length < 2) vaKoordinat[1] = 0;
  vaKoordinat[0] = parseFloat(vaKoordinat[0]);
  vaKoordinat[1] = parseFloat(vaKoordinat[1]);

  var times = prayTimes.getTimes(date, vaKoordinat, Math.floor(GetCfg("nTimeZone", 7)));

  vaJadwal.subuh.adzan = Time2Menit(times.fajr) + 2 + Math.floor(GetCfg("nSubuh", 0));
  vaJadwal.terbit.adzan = Time2Menit(times.sunrise) + 2;
  vaJadwal.dzuhur.adzan = Time2Menit(times.dhuhr) + 2 + Math.floor(GetCfg("nDzuhur", 0));
  vaJadwal.ashar.adzan = Time2Menit(times.asr) + 2 + Math.floor(GetCfg("nAshar", 0));
  vaJadwal.maghrib.adzan = Time2Menit(times.maghrib) + 2 + Math.floor(GetCfg("nMaghrib", 0));
  vaJadwal.isya.adzan = Time2Menit(times.isha) + 2 + Math.floor(GetCfg("nIsya", 0));

  vaJadwal.subuh.iqomah = Math.floor(GetCfg("nSubuh_Iqomah", 0));
  vaJadwal.dzuhur.iqomah = Math.floor(GetCfg("nDzuhur_Iqomah", 0));
  vaJadwal.ashar.iqomah = Math.floor(GetCfg("nAshar_Iqomah", 0));
  vaJadwal.maghrib.iqomah = Math.floor(GetCfg("nMaghrib_Iqomah", 0));
  vaJadwal.isya.iqomah = Math.floor(GetCfg("nIsya_Iqomah", 0));

  if (vaIqomah.sholat == "") {
    // Isi Jadwal Sholat
    _id("cellSubuh").innerText = Menit2Time(vaJadwal.subuh.adzan);
    _id("cellTerbit").innerText = Menit2Time(vaJadwal.terbit.adzan);
    _id("cellDzuhur").innerText = Menit2Time(vaJadwal.dzuhur.adzan);
    _id("cellAshar").innerText = Menit2Time(vaJadwal.ashar.adzan);
    _id("cellMaghrib").innerText = Menit2Time(vaJadwal.maghrib.adzan);
    _id("cellIsya").innerText = Menit2Time(vaJadwal.isya.adzan);
  }

  // Mengatur Warna Tampilan Untuk Jadwal Sholat yang Akan Datang.
  CheckSholat(date);   // Check Apakah Waktunya Adzan kalau adzan kita putar mp3 adzan.
  StopMurotal(date);   // Check Waktu Mematikan Murotal.
  StartMurotal(date);
}

function StartMurotal(d) {
  var nMenit = (d.getHours() * 60) + d.getMinutes();
  var nLamaSholat = 15; // Sholat Di waktu 15 Menit
  var nStart = -1;
  /*
  Menjalankan Murotal
  1. Setelah Sholat ( Adzan + Iqomah + LamaAdzan + Lama Sholat)
  2. Terbit + 1 Menit
  */
  for (var key in vaJadwal) {
    if (key !== "terbit") {
      nStart = vaJadwal[key].adzan + vaJadwal[key].iqomah + nLamaAdzan + nLamaSholat;
    } else {
      nStart = vaJadwal[key].adzan + 1;
    }

    if (nStart == nMenit) {
      ajax("", "StartMurotal", "", function (cData, nSatus) {
        console.log(cData);
      })
    }
  }
}

function StopMurotal(d) {
  // Murotal Kita Hentikan 5 menit sebelum adzan
  var nMenit = (d.getHours() * 60) + d.getMinutes() + 5;
  var lStop = false;
  for (var key in vaJadwal) {
    if (vaJadwal[key].adzan == nMenit) {
      lStop = true;
    }
  }

  // Jika kita pilih mematikan murotal malam hari maka jam 23:00 akan kita matikan
  if (GetCfg("nMatikanMurotalMalam", "Y") == "Y") {
    // Selain itu jika jam 23:00 juga kita matikan murotal
    if (d.getHours() == 23 && d.getMinutes() == 0) lStop = true;
  }

  // Jika Stop 
  if (lStop == true) {
    ajax("", "StopMurotal", "", function (cData, nSatus) {
      console.log(cData);
    })
  }
}

function CapitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function CheckSholat(d) {
  var nMenit = (d.getHours() * 60) + d.getMinutes();
  var cSholat = "Subuh";
  var nWaktuSholat = 0;
  var nBlink = -1;   // Untuk Mendefinisikan Waktu Adzan Kalau masih di Bawah itu maka akan kita buat Berkerdip  
  var lFound = false;
  var cNext = "Subuh";

  for (var key in vaJadwal) {
    // Buat Character 1 Capital
    cSholat = CapitalizeFirstLetter(key);

    // Hitung Waktu Terakhir Iqomah ( Waktu Adzan + Waktu Iqomah + Lama Adzan)
    nWaktuSholat = vaJadwal[key].adzan + vaJadwal[key].iqomah + nLamaAdzan;

    if (key == "terbit") {
      // Jika Matahari Terbit Buat beep Untuk penanda
      if (vaJadwal[key].adzan == nMenit) {
        ajax("", "TerbitStart", "", function (cData) {
          console.log(cData);
        });
      }
    } else {
      if (vaJadwal[key].adzan == nMenit) {
        vaIqomah.start = vaJadwal[key].adzan + nLamaAdzan;
        vaIqomah.end = nWaktuSholat;
        vaIqomah.sholat = cSholat;

        ajax("", "AdzanStart", "Waktu=" + cSholat, function (cData) {
          console.log(cData);
        });
      }
    }

    // Atur Warna Cell Waktu Sholat
    // Kita akan tunggu 15 menit sebelum ke jadwal berikut nya
    if (nMenit <= nWaktuSholat + 15 && !lFound) {
      lFound = true;
      cNext = cSholat;
      nBlink = nMenit - vaJadwal[key].adzan;
    }
  }
  //var cBlink = nBlink >= 0 && nBlink < nLamaAdzan ? " cellBlink" : "";
  var cBlink = vaIqomah.sholat !== "" ? " cellBlink" : "";
  // Jadwal Sholat Berikutnya kita ganti font color supaya lebih kelihatan
  var cells = document.getElementsByClassName("cellJadwalNext");
  for (var i = 0; i < cells.length; i++) {
    cells[i].className = "cellJadwal";
  }

  var cell = _id("cell" + cNext);
  if (cell !== null) cell.className = "cellJadwal cellJadwalNext" + cBlink;

  // Judul Jadwal
  cells = document.getElementsByClassName("cellWaktu");
  for (var i = 0; i < cells.length; i++) {
    cells[i].className = "cellWaktu";
  }

  cell = _id("cell" + cNext + "-Title");
  if (cell !== null) cell.className = "cellWaktu cellWaktuNext";
}

function _id(cID) {
  return document.getElementById(cID);
}

function ReloadPage() {
  location.reload();
}

function addZero(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}

function showTime() {
  var d = new Date();

  // Kita Taruh di atas Biar Kalau ada Error di script aplikasi masih tetap jalan / loop terus
  setTimeout(showTime, 1000);

  if (cellJam == null) cellJam = _id("cellJam");
  cellJam.innerHTML = addZero(d.getHours()) + ":" + addZero(d.getMinutes()) + ":" + addZero(d.getSeconds());

  if (nCheckJadwal !== d.getMinutes()) {
    nCheckJadwal = d.getMinutes();
    ajax("", "CheckConfig", "", function (cData) {
      var va = JSON.parse(cData);
      for (var k in va) {
        SaveCfg(k, va[k]);
      }

      if (GetCfg("Reload", "0") == "1") {
        console.log("Reload");
        ReloadPage();
      }
      nLamaAdzan = Math.floor(GetCfg("nLamaAdzan", 3));
      jadwalSholat();
    });
  }

  // Check Jadwal Iqoma kalau ada isinya kita isi waktu Iqoma
  var nMenit = (d.getHours() * 60) + d.getMinutes();
  if (vaIqomah.sholat !== "" && nMenit >= vaIqomah.start && nMenit <= vaIqomah.end) {
    var nDetik = (vaIqomah.end * 60) - (nMenit * 60) - d.getSeconds();
    var cell = _id("cell" + vaIqomah.sholat);
    var cellTitle = _id("cell" + vaIqomah.sholat + "-Title");
    if (nDetik >= 0) {
      if (cell !== null) cell.innerText = "-" + Menit2Time(nDetik);
      if (cellTitle !== null) cellTitle.innerText = "Iqomah";
      if (nDetik == 0) {
        ajax("", "IqomahStart", "", function (cData) {
          console.log(cData);
        });
      }
    } else if (nDetik < -55) {
      if (cellTitle !== null) cellTitle.innerText = vaIqomah.sholat;
      vaIqomah.start = -1;
      vaIqomah.end = -1;
      vaIqomah.sholat = "";
    }
  }

  // Setiap 5 detik sekali jalankan ajax cek ayat yang sedang di baca
  if (d.getSeconds() % 5 == 0) {
    ajax("", "CheckAyat", "", function (cData) {
      var cell = _id("cellSurat");
      if (cell !== null) {
        cell.innerHTML = "Murotal : " + cData;
      }
    });
  }
}