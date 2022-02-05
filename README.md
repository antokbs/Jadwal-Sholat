Cara Installasi :
1. Copy ke Folder Aplikasi yang dinginkan contoh /home/pi/apps
2. Buat Service yang akan di jalankan pada saat start pada file /home/pi/.bashrc tambahkan baris

   /home/pi/apps/bin/murotal > /dev/null 2>/dev/null &

   php -S 0.0.0.0:8080 -t /home/pi/apps/

3. Startup Jadwal Sholat dengan membuka Browser Chromium pada Startup

    nano ~/.config/lxsession/LXDE-pi/autostart

4. Tambahkan Perintah

    @lxpanel --profile LXDE-pi

    @pcmanfm --desktop --profile LXDE-pi

    @xscreensaver -no-splash

    # Jalankan Mode incognito
    # url http://localhost:8080/sholat/sholat/ selain localhost tidak bisa menjalankan perintah adzan dll ( menjalankan perintah memutar mp3 )
    @chromium-browser --kiosk --incognito http://localhost:8080/sholat/

    # Hiden Mouse
    @unclutter -idle 0    
