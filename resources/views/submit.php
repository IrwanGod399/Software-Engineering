<?php
    session_start();
    $connect = new mysqli("localhost", "root", "", "forum");

    if($connect->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            die("Email dan password tidak boleh kosong.");
        }

        $check_email = $connect->prepare("SELECT email FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();
        
        if($check_email->num_rows > 0){
            header("Location: register.html");
            die();
        }

        $hashpassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $connect->prepare("INSERT INTO users(email, passwd) VALUES(?, ?)");
        $stmt->bind_param("ss", $email, $hashpassword);
        if($stmt->execute()){
            header("location: home.html");
        }
        else{
            echo "error";
        }
        $stmt->close();
        $connect->close();


    }

?>