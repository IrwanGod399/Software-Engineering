<?php
    session_start();
    
    $message = "";
    $error_message = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST['email']; 
        $code = $_POST['code'];
        $connect = new mysqli("localhost", "root", "", "forum");
        $stmt = $connect->prepare("SELECT verification_code FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($db_code);
        $stmt->fetch();

        if($db_code == $code){
            $stmt = $connect->prepare("UPDATE users SET is_valid = 1 WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $message = "Redirecting to login...";
            echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 3000);
            </script>";
        }
        else{
            $error_message = "Kode Salah";
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"   integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify</title>
    </head>
    <body class="d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="width: 30vw; border-radius: 10px;">
            <h4 class="text-center">Verify Code</h4>
            
            <form id="verifyCode" action="verify.php" method="POST">
                <div class="mb-3 text-center">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
                    <label for="verifCode">Enter Code Sending to Your Email</label><br>
                    <input type="text" class="text-center" name="code" placeholder="Enter Code">
                </div>
                <div class="text-center">
                <p class="text-danger text-center"><?php echo $message;?></p>
                    <button type="submit" class="btn btn-primary" style="background-color: rgba(255, 0, 0, 0.808);" >Verify</button>
                </div>
                
            </form>


        </div>
    </body>
</html>