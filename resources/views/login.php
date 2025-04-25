<?php
    session_start();
    if (isset($_SESSION["username"])) {
        header("Location: home.php");
        exit;
    }
    $error_message = "";
    $connect = new mysqli("localhost", "root", "anjing2024", "cyberforum");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);
        if (empty($email) || empty($password)) {
            die("Email dan password tidak boleh kosong.");
        }
        $verify = $connect->prepare("SELECT passwd FROM users WHERE email = ?");
        $verify->bind_param("s", $email);
        $verify->execute();
        $verify->store_result();
        
        if($verify->num_rows == 0){
            $error_message = "Email Tidak Ada";
        }
        else{
            $verify->bind_result($db_passwd);
            $verify->fetch();
            $ver = password_verify($password, $db_passwd);
            if($ver){
                setcookie("user_email", $email, time() + (86400 * 30), "/"); // Berlaku 30 hari
                setcookie("login_status", "verified", time() + (86400 * 30), "/");
                session_start();
                
                $_SESSION["username"] = $email;
                $_SESSION["role"] = "user";
                header("Location: home.php");
            }
            else{
                echo password_verify($password, $db_passwd);
                $error_message = "Password salah";
            }
        }
        $verify->close();

    }
?>


<!DOCTYPE html>

<html>

    <head>
        <title>Login</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"   integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>

    <body class="bg-light d-flex justify-content-center align-items-center vh-100">

        <div class="card p-4 shadow-lg" style="width: 350px; border-radius: 10px;">
            <a href="/register">aaaa</a>
            <h4 class="text-center">Login</h4>
            <form action="index.php" method="POST">
                <div class="mb-3" >
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="Masukkan email" name="email" required>
                    </div>
                </div>
    
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Masukkan password" name="password" required>
                    </div>
                </div>
                <p class="text-danger text-center"><?php echo $error_message;?></p>
                <button type="submit" class="btn w-100" style="background-color: rgba(255, 0, 0, 0.808);" >Login</button>
            </form>
            <p class="text-center mt-3"><a href="register.php" style="color: rgba(255, 0, 0, 0.808);">Register</a></p>
            <p class="text-center mt-3"><a href="home.html" style="color: rgba(255, 0, 0, 0.808);">Lupa password?</a></p>
        </div>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>