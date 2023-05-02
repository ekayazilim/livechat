<?php
// config.php dosyasını dahil ediyoruz
require_once "config.php";

// Değişkenleri tanımlıyoruz
$name = $email = "";
$name_err = $email_err = "";

// Form gönderildiğinde
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Ad alanı boş mu diye kontrol ediyoruz
    if(empty(trim($_POST["name"]))){
        $name_err = "Lütfen adınızı girin.";
    } else{
        $name = trim($_POST["name"]);
    }

    // E-posta alanı boş mu diye kontrol ediyoruz
    if(empty(trim($_POST["email"]))){
        $email_err = "Lütfen e-posta adresinizi girin.";
    } else{
        // E-posta adresinin geçerli olup olmadığını kontrol ediyoruz
        if(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
            $email_err = "Lütfen geçerli bir e-posta adresi girin.";
        } else{
            $email = trim($_POST["email"]);
        }
    }

    // Hata yoksa, kullanıcının bilgilerini veritabanına kaydediyoruz
    if(empty($name_err) && empty($email_err)){
        
        // Veritabanına sorgu gönderiyoruz
        $sql = "INSERT INTO eka_users (name, email, chat_id) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Parametrelerimizi bağlıyoruz
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_chat_id);
            
            // Değerleri ayarlıyoruz
            $param_name = $name;
            $param_email = $email;
            $param_chat_id = uniqid();
            
            // Sorguyu çalıştırıyoruz
            if(mysqli_stmt_execute($stmt)){
                // Yeni bir oturum açıyoruz ve sohbete yönlendiriyoruz
                session_start();
                $_SESSION["chat_id"] = $param_chat_id;      
                header("location: chat.php");
            } else{
                echo "Bir şeyler yanlış gitti. Lütfen daha sonra tekrar deneyin.";
            }

            // İfadeyi kapatıyoruz
            mysqli_stmt_close($stmt);
        }
    }
    
    // Veritabanı bağlantısını kapattık
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Eka Chat - Başla</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

<!-- Header -->
<?php include_once "menu.php"; ?>

<!-- Body -->
<section>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5>Başlayalım</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                                <label>Adınız</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                                <span class="help-block"><?php echo $name_err; ?></span>
                            </div>    
                            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                <label>E-posta Adresiniz</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                                <span class="help-block"><?php echo $email_err; ?></span>
</div>
<div class="form-group mt-3">
<input type="submit" class="btn btn-primary" value="Devam et">
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

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
