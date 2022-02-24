<?php
    $id = $_POST["id"];
    $pass = $_POST["pass"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $register_day = date("Y-m-d");
    


    //id, pass, name, email, register_day
    include "./db_con.php";
    $sql = "insert into register (id, pass, name, email, register_day) values('$id', '$pass', '$name', '$email', '$register_day')";

    mysqli_query($con, $sql);
    mysqli_close($con);

    echo("
        <script>
            location.href = './login.html';
        </script>
    ")    

?>