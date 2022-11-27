<?php
// Страница авторизации пользователей

error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);

function genHash($len = 6)                                                             // Генерация случайной строки хэша
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    return password_hash(substr(str_shuffle($chars), 0, $len),PASSWORD_BCRYPT);
}

$db = new mysqli("lab03", "Ocsid", "95011", "php_lab03");

try {
    if ($db->connect_errno) {
        throw new RuntimeException('Ошибка соединения mysqli: ' . $db->connect_error);
    }
} catch (Exception $err) {
    exit($err->getMessage());
}

if(isset($_POST['submit']))
{
    if( $_POST['password'] && $_POST['login']){                                                             // Проверка на валидность формы
        $query = $db->query("SELECT id, password FROM Users WHERE login='{$_POST['login']}' LIMIT 1");
        $data = $query->fetch_assoc();
        if(password_verify($_POST['password'], $data['password']))                                          // Проверка пароля с БД
        {
            $hash = genHash(10);
            $db->query("UPDATE Users SET hash='{$hash}' WHERE id='{$data['id']}'");
            setcookie("id_user", $data['id'], time()+60*60*24*7);  // время сохранения куки - 7 дней
            setcookie("psw_hash", $hash, time()+60*60*24*7);
            header("Location: check.php");                                               // переадресация на страницу внутренней системы
            exit();
        }
        else
            echo "Неверный логин или пароль";
    }
    else
        echo "Неверный логин или пароль";
}

if (isset($_COOKIE['id_user']) && isset($_COOKIE['psw_hash']))                                              // Если уже есть вход - переадресация
{
    $query = $db->query("SELECT * FROM Users WHERE id = {$_COOKIE['id_user']} LIMIT 1");
    $user = $query->fetch_assoc();
    if(($user['hash'] == $_COOKIE['psw_hash']) && ($user['id'] == $_COOKIE['id_user']))                     // проверка на совпадение данных
    {
        header("Location: check.php");          // переадресация на внутреннюю систему
        exit();
    }
}
?>

<p>Ещё нет аккаунта? <a href="registration.php">Зарегистрироваться</a> </p> <br>
<form method="POST">
    <label> Логин <input name="login" type="text"></label><br><br>
    <label> Пароль <input name="password" type="password"></label><br><br>
    <button name="submit" type="submit">Войти</button>
</form>