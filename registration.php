<?php
// Страница регистрации нового пользователя

error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);

$db = new mysqli("lab03", "Ocsid", "95011", "php_lab03"); // подключение к БД

try {
    if ($db->connect_errno) {
        throw new RuntimeException('Ошибка соединения mysqli: ' . $db->connect_error);
    }
} catch (Exception $err) {
    exit($err->getMessage());
}

if(isset($_POST['submit']))
{
    $err = array();
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))                             // Проверка на соответствие шаблону логина
        $err[] = "Логин должен быть составлен строго из букв английского языка или цифр";
    if(strlen($_POST['login']) < 4 or strlen($_POST['login']) > 20)                        // Проверка на валидность длины логина
        $err[] = "Логин должен содержать не менее 4-х символов и не более 20";
    if(strlen($_POST['password']) < 6)                                                     // Проверка на валидность длины пароля
        $err[] = "Пароль должен содержать не менее 6-ти символов";
    if(strlen($_POST['password']) > 256)                                                   // Проверка на валидность длины пароля
        $err[] = "Пароль должен содержать не более 256-ти символов";
    $query = $db->query("SELECT id FROM Users WHERE login='{$_POST['login']}'");
    if ($query->num_rows)                                                                  // Проверка на занятость логина
        $err[] = "Данный логин уже занят";
    if(count($err) == 0)                                                                   // Если форма полностью валидна, то создаём пользователя
    {
        $login = $_POST['login'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $query = $db->query("INSERT INTO users (login, password, hash) VALUES ('{$login}', '{$password}', NULL)");
        header("Location: login.php");                                              // перенаправление на страницу авторизации
        exit();
    }
    else
    {
        print "<h4>Регистрация невозможна:</h4>";
        foreach($err as $e)
            echo $e."<br>";
    }
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

<p>Уже зарегистрированы? <a href="login.php">Войти</a> </p> <br>
<form method="POST">
    <label> Логин <input name="login" type="text"></label><br><br>
    <label> Пароль <input name="password" type="password"></label><br><br>
    <button name="submit" type="submit">Зарегистрироваться</button>
</form>