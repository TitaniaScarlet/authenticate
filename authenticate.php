<?php

require_once "login.php";
$conn = new mysqli($hn, $ur, $pw, $db);
if($conn->connect_error) die($conn->connect_error);
?>
<pre>
<form action="authenticate.php" method = "post">
    Логин <input type = "text" name="username">
    Пароль<input type="password" name="password">
    Запомнить<input type="checkbox" name="remember">
    <input type="submit" value="Войти">
</form> </pre>
<form action="setupusers.php" method="post">
    <input type="submit" value="Зарегистрироваться">
</form>
<?php
if(isset($_POST['username']) && isset($_POST['password']))
    {
        $username = mysql_entities_fix_string($conn, $_POST['username']);
        $password = mysql_entities_fix_string($conn, $_POST['password']);
        $query = "SELECT *FROM users WHERE username='$username'";
        $result = $conn->query($query);
        if (!$result) die($conn->error);
        elseif ($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            $salt1 = "gm&h*";
            $salt2 = "pg!@";
            $token = hash('ripemd128', "$salt1$password$salt2");
            if($token == $row[3])
            {


                              if (isset($_POST['remember']))
                {
                    session_start();
                    $_SESSION['username'] = $username;
                    setcookie('session_name' , $username , time()+3600*24*7, '/');
                    header("Location: continue.php");

                }
                else{
                    session_start();
                    $_SESSION['username'] = $username;
                    header("Location: continue.php");
                }

            }
            else die("Неверная комбинация имя пользователя - пароль");
        }
        else die("Неверная комбинация имя пользователя - пароль");
    }
else {
    die("Пожалуйста, введите имя пользователя и пароль или зарегистрируйтесь");
}
$conn->close();
function mysql_entities_fix_string($conn, $string)
{
    return htmlentities(mysql_fix_string($conn, $string));
}
function mysql_fix_string($conn, $string)
{
    if(get_magic_quotes_gpc())
        $string = stripslashes($string);
    $string = $conn->real_escape_string($string);
    return $string;
}


?>
