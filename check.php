<?php
// Скрипт проверки на автологинирование для внутренней системы

error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);

$db = new mysqli("lab03", "Ocsid", "95011", "php_lab03");

try {
    if ($db->connect_errno) {
        throw new RuntimeException('Ошибка соединения mysqli: ' . $db->connect_error);
    }
} catch (Exception $err) {
    exit($err->getMessage());
}

if (isset($_COOKIE['id_user']) && isset($_COOKIE['psw_hash']))                                             // Проверка на наличие данных в куки
{
    $query = $db->query("SELECT * FROM users WHERE id = {$_COOKIE['id_user']} LIMIT 1");
    $user = $query->fetch_assoc();
    if(($user['hash'] != $_COOKIE['psw_hash']) || ($user['id'] != $_COOKIE['id_user']) )                   // Если куки не совпадает с данными БД
    {
        setcookie("id_user", "", time() - 60*60*24*7*12, "/");
        setcookie("psw_hash", "", time() - 60*60*24*7*12, "/");
        print 'В доступе отказано<br><br><a href="login.php">Войти</a>';
    }
    else                                                                                                   // Если куки совпали, то велкам
    {
        print "Рады видеть Вас снова, ".$user['login'].".<br><br>";
        print '<form method="POST"><input name="submit" type="submit" value="Выйти"></form>';
    }
}
else
    print 'Страница входа в систему<br><br><a href="login.php">Войти</a>';

if(isset($_POST['submit']))
{
    $db->query("UPDATE Users SET hash='' WHERE id='{$_COOKIE['id_user']}'");
    setcookie("id_user", null, -1, "/");
    unset($_COOKIE['id_user']);
    unset($_COOKIE['psw_hash']);
    setcookie("psw_hash", null, -1, "/");
    header("Location: login.php");                                                                 // переадресация на страницу входа
    exit();
}
