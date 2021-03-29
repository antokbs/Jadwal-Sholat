var nCheckJadwal = -1;
var vaJadwal = {
  "subuh": { "adzan": 0, "iqomah": 0 },
  "terbit": { "adzan": 0, "iqomah": 10 },
  "dzuhur": { "adzan": 0, "iqomah": 0 },
  "ashar": { "adzan": 0, "iqomah": 0 },
  "maghrib": { "adzan": 0, "iqomah": 0 },
  "isya": { "adzan": 0, "iqomah": 0 }
};
var cellJam = null;
var vaIqomah = { "start": -1, "end": -1, "sholat": "" };
var nLamaAdzan = 3; // Untuk Waktu Adzan Kita toleransi 3 Menit
var vaSholatMalam = { "satu": 90, "dua": 60 }; // Untuk Reminder Sholat Malam 1.5 Jam Sebelum Subuh dan 1 Jam Sebelum subuh
function LoadForm(url) {
  BASE_URL = url;
  showTime();
}

function jadwalSholat(d) {
  prayTimes.setMethod("INDONESIA");
  var vaKoordinat = GetCfg("cKoordinat", "0,0").split(",");

  if (vaKoordinat.length < 2) vaKoordinat[1] = 0;
  vaKoordinat[0] = parseFloat(vaKoordinat[0]);
  vaKoordinat[1] = parseFloat(vaKoordinat[1]);

  var times = prayTimes.getTimes(d, vaKoordinat, Math.floor(GetCfg("nTimeZone", 7)));

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
  CheckSholat(d);    // Check Apakah Waktunya Adzan kalau adzan kita putar mp3 adzan.
  StopMurotal(d);    // Check Waktu Mematikan Murotal.
  StartMurotal(d);   // Check Waktu Menjalankan Murotal
  CheckSholatMalam(d);
}

function CheckSholatMalam(d) {
  var nMenit = (d.getHours() * 60) + d.getMinutes();
  for (var key in vaSholatMalam) {
    if (vaSholatMalam[key] !== 0) {
      if (vaJadwal.subuh.adzan - vaSholatMalam[key] == nMenit) {
        ajax("", "SholatMalam", "", function (cData) {
          ShowMessage("Waktunya Sholat Malam ...");
          console.log(cData);
        })
      }
    }
  }
}

function StartMurotal(d) {
  var nMenit = (d.getHours() * 60) + d.getMinutes();
  var nLamaSholat = 15; // Sholat Di waktu 15 Menit
  var nStart = -1;
  var lStart = false;
  /*
  Menjalankan Murotal
  1. Setelah Sholat ( Adzan + Iqomah + LamaAdzan + Lama Sholat)
  2. Terbit + 1 Menit
  */
  for (var key in vaJadwal) {
    if (key !== "terbit" && key !== "subuh" && key !== "maghrib") {
      nStart = vaJadwal[key].adzan + vaJadwal[key].iqomah + nLamaAdzan + nLamaSholat;
    } else if (key == "terbit") {
      nStart = vaJadwal[key].adzan + 1;
    } else {
      // Jika Antara Subuh Ke Shuruq atau Magrib Ke Isya kita beri tulisan Murotal Stoped
      if (vaJadwal[key].adzan + vaJadwal[key].iqomah + nLamaAdzan + nLamaSholat == nMenit) {
        ShowMessage("Murotal Stoped ....");
      }
    }

    if (nStart == nMenit) lStart = true;

  }

  /*
  Murotal akan kita jalankan 2 Menit setelah penanda Sholat Malam dengan catatan
  1. Data Bukan 0
  2. MatikanmutotalMalam == "T"
  */
  if (GetCfg("nMatikanMurotalMalam", "Y") == "T") {
    for (var key in vaSholatMalam) {
      if (vaSholatMalam[key] !== 0) {
        if (vaJadwal.subuh.adzan - vaSholatMalam[key] + 2 == nMenit) {
          lStart = true;
        }
      }
    }
  }

  if (lStart) {
    ajax("", "StartMurotal", "", function (cData) {
      console.log(cData);
    })
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

  /* Untuk Pengingat Sholat Malam 
  1. 1.5 Jam Sebelum subuh
  2. 1 Jam Sebelum Subuh
  */
  for (var key in vaSholatMalam) {
    if (vaSholatMalam[key] !== 0) {
      if (vaJadwal.subuh.adzan - vaSholatMalam[key] == nMenit) {
        lStop = true;
      }
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
          ShowMessage("Matahari Terbit ...");
        });
      }
    } else {
      if (vaJadwal[key].adzan == nMenit) {
        vaIqomah.start = vaJadwal[key].adzan + nLamaAdzan;
        vaIqomah.end = nWaktuSholat;
        vaIqomah.sholat = cSholat;

        ajax("", "AdzanStart", "Waktu=" + cSholat, function (cData) {
          console.log(cData);
          ShowMessage("Adzan Sholat " + vaIqomah.sholat);
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

function ReloadPage() {
  location.reload();
}

function getNow(){
  var d = new Date() ;

  // Timezone sesuai yang di Setting di config Jadiman Menit
  var nTimeZone = Math.floor(GetCfg("nTimeZone",7)) * 60 ;
  
  // Kita tambak dengan Timezone yang ada di Komputer
  // Ini supaya kita bisa setting timezone tanpa harus merubah timezone komputer
  nTimeZone += d.getTimezoneOffset() ;
  d.setMinutes(d.getMinutes()+nTimeZone) ;
  return d ;
}

function showTime() {
  var d = getNow() ;

  // Kita Taruh di atas Biar Kalau ada Error di script aplikasi masih tetap jalan / loop terus
  setTimeout(showTime, 1000);

  if (cellJam == null) cellJam = _id("cellJam");
  cellJam.innerHTML = addZero(d.getHours()) + ":" + addZero(d.getMinutes()) + ":" + addZero(d.getSeconds());

  if (nCheckJadwal !== d.getMinutes()) {
    nCheckJadwal = d.getMinutes();
    initConfig(function (cData) {
      nLamaAdzan = Math.floor(GetCfg("nLamaAdzan", 3));
      jadwalSholat(d);

      if (GetCfg("Reload", "0") == "1") {
        console.log("Reload");
        ReloadPage();
      }
    });
  }

  // Check Jadwal Iqoma kalau ada isinya kita isi waktu Iqoma
  var nMenit = (d.getHours() * 60) + d.getMinutes();
  if (vaIqomah.sholat !== "" && nMenit >= vaIqomah.start && nMenit <= vaIqomah.end) {
    var nDetik = (vaIqomah.end * 60) - (nMenit * 60) - d.getSeconds();
    var cell = _id("cell" + vaIqomah.sholat);
    var cellTitle = _id("cell" + vaIqomah.sholat + "-Title");
    if (nDetik >= 0) {
      //if (cell !== null) cell.innerText = "-" + Menit2Time(nDetik);
      //if (cellTitle !== null) cellTitle.innerText = "Iqomah";
      if (nDetik == 0) {
        ajax("", "IqomahStart", "", function (cData) {
          console.log(cData);
          ShowMessage("Waktu Sholat " + vaIqomah.sholat);
        });
      } else {
        ShowMessage("Iqomah - " + Menit2Time(nDetik));
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
      var vaData = JSON.parse(cData);
      if (vaData.status == "start") {
        ShowMessage(vaData.ayat);
      }
    });
    GetNamaHari(d);
  }
}

function ShowMessage(cMessage) {
  var cell = _id("cellSurat");
  if (cell !== null && cMessage !== "") {
    cell.innerHTML = cMessage;
  }
}

var lHijriah = false;
function GetNamaHari(d) {
  var vaHari = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
  var vaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  var c = vaHari[d.getDay()] + ", ";
  lHijriah = !lHijriah;
  if (lHijriah) {
    c += GetCfg("hijriah", "");
  } else {
    c += addZero(d.getDate()) + " " + vaBulan[d.getMonth()] + " " + d.getFullYear().toString();
  }
  _id("cellTanggal").innerText = c;
}