<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Life+Savers:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header class="menu">
        <nav>
            <ul>
                <li><a href="index.html">Strona główna</a></li>
                <li><a href="gitary_klasyczne.html">Gitary klasyczne</a></li>
                <li><a href="gitary_akustyczne.html">Gitary akustyczne</a></li>
                <li><a href="gitary_elektryczne.html">Gitary elektryczne</a></li>
            </ul>
            <a href="login.php" class="login_button"><span id="login_button_napis">Zaloguj się</span></a>
        </nav>
    </header>
    <main id="str_kont">
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="kontakt_form">
        <h1 id="contact_name">Napisz do nas!</h1>
        <label for="email">E-Mail:</label>
        <input type="email" name="email" id="email" required placeholder="jan.kowalski@gmail.com">
        <br>
        <textarea name="message" id="message" required placeholder="Twoja wiadomość..."></textarea>
        <input type="submit" value="Wyślij" name="submit" id="submit">
        <br>
        <?php
            require "db-connection.php";
            $info = '';
            $info_success = '';
            if ($_SERVER["REQUEST_METHOD"] ==="POST"){
                $email = $_POST['email'];
                $message = $_POST['message'];
                try{
                    $sql = "INSERT INTO wiadomosci (email, message) VALUES (?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$email, $message]);
                    $info_success = "Twoja wiadomość została wysłana";
                }catch (PDOException $e){
                    $info = "Coś poszło nie tak. Spróbuj ponownie.";
                }
            }
?>
        <?php if($info): ?>
            <p class="msg"><?= htmlspecialchars($info) ?></p>
        <?php elseif($info_success): ?>
            <p class="msgSc"><?= htmlspecialchars($info_success) ?></p>
        <?php endif; ?>
    </form>
    </main>

</body>
</html>




