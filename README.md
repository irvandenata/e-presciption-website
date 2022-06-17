## Deskripsi
Aplikasi dibangun dengan menggunakan database MySql dengan 6 tabel utama.
    ```
	 obat_resep_signas
	 obatalkes_m
	 resep_signas
	 reseps
	 signa_m
	 users 
    ```

Aplikasi berguna untuk melakukan manajemen resep dengan input berupa sekumpulan obat dengan jenis Racikan dan Non Racikan.

## Instruksi Instalasi
1. clone project dengan menggunakan git.
github : https://github.com/irvandenata/e-prescription-website.git
2. buka direktori pada folder yang telah di clone
3. lakukan instalasi vendor dengan menggunakan composer.
4. jalankan perintah 
``` 
composer install
```
5. buat database mysql kemudian import tabel yang telah di sediankan masterobat dan mastersigna
6. buat file .env berdasarkan .env.example
7. pada file .env setting akun mysql dan database sesuai dengan yang dimiliki
8. lakukan generate key untuk aplikasi dengan menjalankan perintah 
```
php artisan key:generate pada terminal
```
9. lakukan Perinta Migrasi untuk membuat tabel dan user yang telah di buat pada program dengan perintah php artisan migrate --seed
10. Jalan kan webserver agar aplikasi dapat dibuka dengan perintah 

```
php artisan serve
```
11. aplikasi dapat di buka dengan mengakses url  localhost:8000 (8000 port default dapat berubah)  pada browser. 
12. akun dari seeder 

```
Email : test@gmail.com
password : password
```

