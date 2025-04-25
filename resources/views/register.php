<?php
    session_start();
    $connect = new mysqli("localhost", "root", "anjing2024", "cyberforum");

    if($connect->connect_error){
        die("Connection failed: " . $connect->connect_error);
    }
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    $email_exist = "";
    function sendVerificationCode($email, $verification_code){
        $mail = new PHPMailer(true);
                try {
                    echo "AAAAA";
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'iniphisingterbaru2026@gmail.com'; 
                    $mail->Password = 'dxkc zugp yiwx pjws';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                
                    $mail->setFrom('iniphisingterbaru2026@gmail.com', 'Jangan Dikasih Siapa2');
                    $mail->addAddress($email);
                    $mail->Subject = 'Kode Verifikasi Akun';
                    $mail->Body = "Kode verifikasi kamu adalah: $verification_code";
                    echo "CCCC";
                    $mail->send();
                    echo "DDD";
                    
                    echo "<p style='color: green;'>Kode verifikasi telah dikirim ke email.</p>";
                    header("Location: verify.php?email=$email");
                    exit();
                } catch (Exception $e) {
                    $email_exist = $mail->ErrorInfo;
                }
    }


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);
        $verification_code = rand(100000, 999999);
        if (empty($email) || empty($password)) {
            die("Email dan password tidak boleh kosong.");
        }

        $check_email = $connect->prepare("SELECT is_valid FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();
        $check_email->bind_result($is_valid);
        $check_email->fetch();


        if ($check_email->num_rows > 0) {
            if($is_valid == 1){
                $email_exist = "Email already registered";
                $check_email->close(); 
            }
            else{
                $email_exist = "Sending Verification Code";
                $stmt = $connect->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
                $stmt->bind_param("is", $verification_code, $email);
                $stmt->execute();
                $stmt->close();
                sendVerificationCode($email, $verification_code);
            }
        }
        else{
            $verification_code = rand(100000, 999999);
            $stmt = $connect->prepare("INSERT INTO users(email, passwd, verification_code) VALUES(?, ?, ?)");
            $stmt->bind_param("ssi", $email, $hashpassword, $verification_code);
            $stmt->execute();
            $stmt->close();
            $email_exist = "Sending Verification Code";
            sendVerificationCode($email, $verification_code);
        }
        $check_email->close();
        $connect->close();
    }

?>

<!DOCTYPE html>

<html>

    <head>
        <title>Register</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"   integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>

    <body class="bg-light d-flex justify-content-center align-items-center vh-100">

        <div class="card p-4 shadow-lg" style="width: 350px; border-radius: 10px;">
            <h4 class="text-center">Register</h4>
            <form id="registerForm" action="register.php" method="POST">
                <div class="mb-3" >
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                    </div>
                </div>
    
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="confirm-password" placeholder="Konfirmasi password" required>
                    </div>
                </div>
                <p class="text-center text-danger" id="errorMessage"></p>
                <p class="text-danger text-center"><?php echo $email_exist;?></p>
                <button type="submit" class="btn w-100" style="background-color: rgba(255, 0, 0, 0.808);" >Register</button>
            </form>
            <p class="text-center mt-3"><a href="login.php" style="color: rgba(255, 0, 0, 0.808);">Already have account?</a></p>
        </div>

        <script>
            document.getElementById("registerForm").addEventListener("submit", function(event){
                let email = document.getElementById("email").value;
                let password = document.getElementById("password").value;
                let confirm_password = document.getElementById("confirm-password").value;
                let errorMessage = document.getElementById("errorMessage");
                
                if(!email.endsWith("@gmail.com")){
                    errorMessage.textContent = "Email tidak valid";
                    event.preventDefault();
                }
                
                if(password !== confirm_password){
                    errorMessage.textContent = "Password doesn't match!"
                    console.log(password, confirm_password)
                    event.preventDefault();
                }

            });


        </script>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>