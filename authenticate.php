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
        $stmt = $conn->prepare('SELECT username, password FROM users WHERE username = ? AND password = ?');
        $stmt->bind_param('ss', $username,$token);
        $username = $_POST['username'];
        $password = $_POST['password'];
        $token = password_verify($password, $hash);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if (!$result) die($conn->error);
        elseif ($result)
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
else {
    die("Пожалуйста, введите имя пользователя и пароль или зарегистрируйтесь");
}
$result->close();
$conn->close();
?>
