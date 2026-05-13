<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Life+Savers:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header class="menu">
        <nav>
            <ul>
                <li><a href="index.html">Powrót</a></li>
                <li><a href="gitary_klasyczne.php">Gitary klasyczne</a></li>
                <li><a href="gitary_akustyczne.php">Gitary akustyczne</a></li>
                <li><a href="gitary_elektryczne.php">Gitary elektryczne</a></li>
                <li><a href="kontakt.php">Kontakt</a></li>
            </ul>
        </nav>
    </header>
    <main id="str_log">
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="login_form">
        <?php
            session_start();
            require_once __DIR__ . '/db-connection.php';
            $info = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = trim($_POST['username']);
                $password = trim($_POST['password']);
                $stmt = $pdo->prepare('SELECT * FROM dane WHERE email = ?');
                $stmt->execute([$name]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['is_admin'] = (bool)$user['type'];
                    if($user['type'] == 1){
                        header('Location: admin_panel.php');
                    } else {
                        header('Location: user_panel.php');
                    }
                    exit;
                } else{
                    $info = 'Nieprawidłowa nazwa użytkownika lub hasło.';
                }
            }
        ?>
        <h1 id="login_name">LOGIN</h1>
        <label for="username">Nazwa użytkownika: </label>
        <input type="email" class="input_group" name="username" required placeholder="jan.kowalski@gmail.com">
        <label for="password">Hasło: </label>
        <input type="password" placeholder="Podaj hasło..." class="input_group" name="password" required>
        <input type="submit" value="Zaloguj się" name="submit" id="submit">
        <p class="msg"><?= htmlspecialchars($info) ?></p>
    </form>
    </main>
    <footer>
        <div id="info_footer">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1563.653310782513!2d19.906288605493938!3d49.99795196489178!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47165d622928bf21%3A0xa6031d056bc3043b!2sKwiatowa%2012%2C%2030-437%20Krak%C3%B3w!5e0!3m2!1spl!2spl!4v1776334370303!5m2!1spl!2spl" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <p id="info">Kontakt:<br>
            Telefon: +48 123 456 789<br>
            Mail: simson_guitars@gmail.com<br>
            Adres: ul. Kwiatowa 12, 30-437 Kraków<br><br>
            Godziny otwarcia:<br>
            Poniedziałek - Piątek: 8:00 - 18:00<br>
            Sobota: 10:00 - 18:00<br>
            Niedziela: Nieczynne</p>
        </div>
    <p>Copyright 2026</p>
    </footer>
</body>
</html>