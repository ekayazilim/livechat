<?php

define('DB_SERVER', 'localhost'); // Veritabanı sunucusu adı veya IP adresi
define('DB_USERNAME', 'kullanici_adi'); // Veritabanı kullanıcı adı
define('DB_PASSWORD', 'sifre'); // Veritabanı şifresi
define('DB_NAME', 'veritabani_adi'); // Veritabanı ismi

// Veritabanına bağlantı oluşturma
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Bağlantı hata kontrolü
if($conn === false){
    die("HATA: Veritabanına bağlanırken hata oluştu. " . mysqli_connect_error());
}
?>
