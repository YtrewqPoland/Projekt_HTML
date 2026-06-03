<?php
require_once __DIR__ . '/db-connection.php';
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || strlen($username) < 3) {
        $errors[] = 'Nazwa użytkownika musi mieć co najmniej 3 znaki.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Nieprawidłowy adres e-mail.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Hasło musi mieć co najmniej 6 znaków.';
    }
    if ($password !== $password2) {
        $errors[] = 'Hasła nie są takie same.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM dane WHERE name = :n OR email = :e');
            $stmt->execute([':n' => $username, ':e' => $email]);
            $exists = (int)$stmt->fetchColumn();
            if ($exists > 0) {
                $errors[] = 'Nazwa użytkownika lub e-mail już istnieje.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins = $pdo->prepare('INSERT INTO dane (name, email, password) VALUES (:n, :e, :p)');
                $ins->execute([':n' => $username, ':e' => $email, ':p' => $hash]);
                $success = 'Rejestracja zakończona pomyślnie. Możesz się teraz zalogować.';
            }
        } catch (Exception $e) {
            $errors[] = 'Błąd bazy danych: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Rejestracja</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<main id="str_register">
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="login_form">
        <h1 id="login_name">REJESTRACJA</h1>

        <?php if ($success): ?>
            <p class="msg" style="color:green"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <ul class="msg" style="color:red">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <label for="username">Nazwa użytkownika</label>
        <input id="username" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
        <label for="email">E-mail</label>
        <input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        <label for="password">Hasło</label>
        <input id="password" name="password" type="password" required>
        <label for="password2">Powtórz hasło</label>
        <input id="password2" name="password2" type="password" required>
        <button type="submit" id="submit">Zarejestruj się</button>
        <a href="login.php" id="register_link">Masz już konto? Zaloguj się</a>
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
