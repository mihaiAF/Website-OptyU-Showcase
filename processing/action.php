<?php

$nume = $_POST['nume'];
$prenume = $_POST['prenume'];
$email = $_POST['email'];
$telefon = $_POST['telefon'];
$description = $_POST['description'];

if (!empty($nume) || !empty($prenume) || !empty($telefon) || !empty($email) || !empty($description)) {

    //Remove illegal characters from fields
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $nume = filter_var($nume, FILTER_SANITIZE_STRING);
    $prenume = filter_var($prenume, FILTER_SANITIZE_STRING);
    $telefon = filter_var($telefon, FILTER_SANITIZE_STRING);
    $description = filter_var($description, FILTER_SANITIZE_STRING);

    //Email validation
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "root";
        $dbName = "optyU";

        //Create connection
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if(mysqli_connect_error()) {
            die('Connect erorr(' . mysqli_connect_errno() . ')' . mysqli_connect_error());
        } else {
            $SELECT = "SELECT email FROM contact WHERE email = ? Limit 10";
            $INSERT = "INSERT INTO contact (nume, prenume, telefon, email, description) values (?, ?, ?, ?, ?)";


            //Prepare statement
            if ($stmt = $conn->prepare($SELECT)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($email);
                $rnum = $stmt->num_rows;

                if ($rnum == 0) {
                    $stmt->close();

                    $stmt = $conn->prepare($INSERT);
                    $stmt->bind_param("sssss", $nume, $prenume, $telefon, $email, $description);
                    $stmt->execute();
                    header('Location: formSent.html');

                } else {
                    echo "Numarul maxim de email-uri a fost atins. Va rugam incercati mai tarziu!";
                }

                $stmt->close();
                $conn->close();

            } else {
                die('Prepare failed');
            }

        }

    } else {
        echo "$email is not a valid email address";
        die();
    }

} else {
    echo "These fields are required";
    die();
}