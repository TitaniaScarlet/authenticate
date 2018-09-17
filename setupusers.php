<?php
require_once "login.php";
$conn = new mysqli($hn, $ur, $pw, $db);
if($conn->connect_error) die($conn->connect_error);
?>
<pre><form action="setupusers.php" method = "post">
    Имя     <input type = "text" name = "forename">
    Фамилия <input type = "text" name = "surname">
    Логин   <input type = "text" name="username" maxlength="15">
    Пароль  <input type="password" name="password">
<input type="submit" value="Зарегистрироваться">
</form> </pre>
<?php
if(isset($_POST['forename']) && isset($_POST['surname']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysql_entities_fix_string($conn, $_POST['username']);
    $password = mysql_entities_fix_string($conn, $_POST['password']);
    $forename = mysql_entities_fix_string($conn, $_POST['forename']);
    $surname = mysql_entities_fix_string($conn, $_POST['surname']);
    $salt1 = "gm&h*";
    $salt2 = "pg!@";
    $token_ins = hash('ripemd128', "$salt1$password$salt2");
    $query = "INSERT INTO users VALUES" . "('$forename', '$surname', '$username', '$token_ins')";
    $result = $conn->query($query);
    if (!$result) echo "Пользователь с таким логином уже существует<br>";
    elseif ($result)
    {
        session_start();
        $_SESSION['username'] = $username;
                header("Location: continue.php");
    }
}
    else {
        die("Пожалуйста, заполните все поля для регистрации");
    }
    $conn->close();
    function mysql_entities_fix_string($conn, $string)
    {
        return htmlentities(mysql_fix_string($conn, $string));
    }

    function mysql_fix_string($conn, $string)
    {
        if (get_magic_quotes_gpc())
            $string = stripslashes($string);
        $string = $conn->real_escape_string($string);
        return $string;
    }
?>

