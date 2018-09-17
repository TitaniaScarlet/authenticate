<?php
require_once "login.php";
$conn = new mysqli($hn, $ur, $pw, $db);
if($conn->connect_error) die($conn->connect_error);
session_start();
if(isset($_SESSION['username']))
{
    $username = $_SESSION['username'];
    echo "Здравствуйтe, вы зарегистрированы под именем $username<br>";
}
elseif (!isset($_SESSION['username']) && isset($_COOKIE['session_name']))
{
    $_SESSION['username'] = $_COOKIE['session_name'];
    $username = $_SESSION['username'];
    echo "Здравствуйтe, вы зарегистрированы под именем $username<br>";

}

if(isset($_POST['exit']))
{

    //$_SESSION = array();
    //if(session_id() != '' || isset($_COOKIE['session_name'])) {
        unset($_COOKIE['session_name']);
        setcookie('session_name', null, time() - 3600 * 24 * 7, '/');
        unset($_SESSION['username']);
        session_destroy();
        header("Location: authenticate.php");


}

?>
<form method="post" >
    <input type="submit" name="exit" value="Выход">
</form>
