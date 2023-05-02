<?php
// session başlatma
session_start();

// Kullanıcı oturum açmadan chat.php'ye erişmeye çalışırsa login.php sayfasına yönlendiriyoruz
if(!isset($_SESSION["chat_id"])){
    header("location: login.php");
    exit;
}

// Veritabanı bağlantısı
require_once "config.php";

// Hata mesajları
$name_err = $msg_err = "";

// Mesaj gönderme işlemi
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // form verilerini kontrol edelim
    if(empty(trim($_POST["name"]))){
        $name_err = "Lütfen bir isim girin.";
    } else{
        $name = trim($_POST["name"]);
    }

    if(empty(trim($_POST["message"]))){
        $msg_err = "Lütfen bir mesaj yazın.";
    } else{
        $message = trim($_POST["message"]);
    }

    // Hatalar yoksa, mesaj veritabanına kaydedilir
    if(empty($name_err) && empty($msg_err)){
        $sql = "INSERT INTO eka_messages (user_id, name, message) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $param_user_id, $param_name, $param_message);

        $param_user_id = $_SESSION["chat_id"];
        $param_name = $name;
        $param_message = $message;

        mysqli_stmt_execute($stmt);

        // Mesaj gönderildikten sonra, form alanları yenilenir
        $name = $message = "";
    }
}

// Sohbet odalarını alıyoruz
$sql = "SELECT * FROM eka_rooms";
$result = mysqli_query($link, $sql);
$rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Mevcut kullanıcının bilgilerini alıyoruz
$sql = "SELECT * FROM eka_users WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $param_id);

$param_id = $_SESSION["chat_id"];
mysqli_stmt_execute($stmt);

$user = mysqli_stmt_get_result($stmt)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Eka Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

<!-- Header -->
<?php include_once "menu.php"; ?>

<!-- Body -->
<section>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5>Sohbet Odaları</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <?php foreach($rooms as $room): ?>
                                <li><a href="room.php?id=<?php echo $room['id']; ?>"><?php echo $room['name']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5>Hosgeldin, <?php echo htmlspecialchars($user["name"]); ?>!</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="name">İsim:</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>">
                                <span class="help-block"><?php echo $name_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="message">Mesaj:</label>
<textarea name="message" id="message" rows="5" class="form-control"><?php echo htmlspecialchars($message); ?></textarea>
<span class="help-block"><?php echo $msg_err; ?></span>
</div>
<div class="form-group">
<input type="submit" value="Gönder" class="btn btn-primary">
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Footer -->
<?php include_once "footer.php"; ?>

<!-- JavaScript dosyaları -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi4jq7">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM">
</script>

</body>
</html>
