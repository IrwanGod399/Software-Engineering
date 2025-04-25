<?php
session_start();
if (!isset($_SESSION["username"])) {
    echo "Anda harus login dulu!";
    exit;
}

// echo "Welcome, " . $_SESSION["username"];
$email = $_SESSION["username"];
$connect = new mysqli("localhost", "root", "", "forum");
$stmt = $connect->prepare("UPDATE users SET alive = 'online' WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->close();
?>

<!DOCTYPE html> 
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            html, body {
                overflow-x: hidden;
            }
        </style>
    </head>
    <body>
        <div style="height: 80px;" class="vw-100 bg-success border">
            <p>Hello</p>
        </div>
        <div class="vh-100 vw-100 d-flex mb-3 border overflow-hidden">
            <div style="width: 1300px" class="bg-danger mb-3 border">
                <p>AAAAA</p>
            </div>
            <div style="width: 300px" class="bg-info mb-3 border">
                <p>BBBBB</p>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

