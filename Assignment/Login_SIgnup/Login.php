<?php
$userName = $password ="";
$userNameErr = $passwordErr ="";

function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty($_POST["userName"])){
        $userNameErr = "Enter a valid username.";
    }else{
        $userName = cleanInput($_POST["userName"]);
        if(!preg_match("/^[a-zA-Z-' ]*$/", $userName)){
            $userNameErr = "Only letters and white space allowed";
        }
    }

    if(empty($_POST["password"])){
        $passwordErr = "Enter the correct pasword";
    }
    else{
        $password = cleanInput($_POST["password"]);

        if(strlen($password)<8)
            {
                $passwordErr = "Enter minimun 8 charecters";
            }
    }

}
?>
<!DOCTYPE html>
<html lang = "en">

<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial=scale=1.0">
    <title>Login Form</title>

    <style>

        body {
            background-color: #7fc5ce;
            font-family: Arial, Helvetica, sans-serif;
        }

        button {
    padding: 8px 50px;
    margin-top: 10px;
    margin-right: 10px;
    border: none;
    border-radius: 4px;
    background-color: #04AA6D;
    color: white;
    font-weight: bold;
    cursor: pointer;
}


button:hover {
    background-color: #038d5a;
}


    </style>

</head>

<body>

    <h1>Welcome Back</h1>
    <p>Log in to continue</p>

     <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="userName" value="<?= $userName ?>">
        <span style="color:red"><?= $userNameErr ?></span><br><br>

        Password: <input type="password" name="password" value="<?= $password ?>">
        <span style="color:red"><?= $passwordErr ?></span><br><br>

        <button type="submit">Log in</button>


        <P>Try: admin/12345 sara/pass123</P>
    </form>

</body>

</html>