# Binotify - Milestone 1 IF3110
Music player berbasis web yang dibuat menggunakan HMTL5, CSS, JS, dan MySQL


## Requirement
1. XAMPP
2. DOCKER

## Cara Menggunakan
### Jika Menggunakan XAMPP
```sh 
git clone https://github.com/afrizalsebastian/Milestone-1-IF3110.git
```
1. Jalankan perintah di atas pada terminal di "htdocs" pada Folder Aplikasi tempat XAMPP terinstall
2. Downlaod [assets](https://drive.google.com/drive/folders/18-64OG7wJp76dd4loc3dVR_Fl4P2TPIF?usp=sharing) dan extract folder songs, img_album, img_song ke assets pada project. Untuk menyesuaikan dengan record yang ada di database
3. Pada folder src/server/config.php, sesuaikan konfigurasi $username dan $password sesuai dengan database masing-masing.
4. Buka Aplikasi XAMPP dan start pada Apache dan MySQL.
5. Jalankan ```localhost/Milestone-1-IF3110/src/frontend/mainpage/index.php``` pada web browser masing-masing.

### Jika Menggunakan docker
```sh 
git clone https://github.com/afrizalsebastian/Milestone-1-IF3110.git
```
1. Jalankan perintah di atas pada terminal folder manapun.
2. Downlaod [assets](https://drive.google.com/drive/folders/18-64OG7wJp76dd4loc3dVR_Fl4P2TPIF?usp=sharing) dan extract folder songs, img_album, img_song ke assets pada project. Untuk menyesuaikan dengan record yang ada di database.
3. Pada folder src/server/config.php, sesuaikan konfigurasi $hostname = 'database', $username = 'root', $password ='rootpass'
4. Pastikan docker dekstop telah terinstal.
5. Lakukan perintah ```docker-compose build``` pada terminal root folder dari project.
6. Setalah itu lakukan perintah ```docker-compose up``` pada terminal folder dari project.
7. Jalankan ```localhost:8000/frontend/mainpage/index.php``` pada web browser masing-masing.

##Pembagian Tugas :

Roy Simbolon
- Halaman Home front-end & back-end
- Halaman detail-lagu back-end


Afrizal Sebastian
- Halaman login front-end & back-end
- Halaman Register front-end & back-end
- Halaman detail album front-end & back-end
- Halaman tambah album/lagu front-end & back-end
- Halaman daftar-user front-end & back-end


Ghebyon Tohada Nainggolan
- Halaman Daftar Album front-end & back-end
- Halaman Search, Sort, and Filter front-end & back-end
- Halaman detail Lagu front-end
