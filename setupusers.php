<?php
require_once "login.php";
$conn = new mysqli($hn, $ur, $pw, $db);
if($conn->connect_error) die($conn->connect_error);
?>
<pre><form action="setupusers.php" method = "post">
    Имя                <input type = "text" name = "forename">
    Фамилия            <input type = "text" name = "surname">
    Имя пользователя   <input type = "text" name="username" > Разрешено от 3 до 20 символов: a-z, A-Z, 0-9, -, _
    Пароль             <input type="password" name="password"> Разрешено от 8 до 20 символов: a-z, A-Z, 0-9
<input type="submit" value="Зарегистрироваться">
</form> </pre>
<?php
if(isset($_POST['forename']) && isset($_POST['surname']) && preg_match('/^[A-z0-9-_]{3,20}$/i', $_POST['username'])
    && preg_match('/^[A-z0-9]{8,20}$/i', $_POST['password'])) {
    $stmt = $conn->prepare('INSERT INTO users VALUES(?,?,?,?)');
    $stmt->bind_param('ssss',$forename,$surname, $username, $hash);
    $username = $_POST['username'];
    $forename = $_POST['forename'];
    $surname = $_POST['surname'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 14]);
    $stmt->execute();
    $result = $stmt->affected_rows;
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
    $result->close();
    $conn->close();
    ?>

