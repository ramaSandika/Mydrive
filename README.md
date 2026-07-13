# MyDrive - Cloud Storage Project 

## Anggota Kelompok
1. I Made Rama Sandika Putra – (2415323014) 
2. I Gusti Made Agung Windu Prayadnya – (2415323074) 
3. Kadek Artha Pramana – (2415323080) 
4. I Gede Mahendra Pratama Adi Putra – (2415323068) 

## Cara Menjalankan Aplikasi
1. Pastikan Docker Desktop aktif, jalankan kontainer:
	`docker-compose up -d`
2. Hidupkan XAMPP (Apache & MySQL). Buat database kosong bernama `mydrive_db`.
3. Salin file .env dan sesuaikan koneksi database.
4. Jalankan perintah migrasi & pembersihan:
	`php artisan migrate`
	`php artisan optimize:clear` 
5. Jalankan server Laravel:
	`php artisan serve`
