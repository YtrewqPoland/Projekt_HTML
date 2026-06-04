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
                <li><a href="gitary_klasyczne.php">Gitary klasyczne</a></li>
                <li><a href="gitary_akustyczne.php">Gitary akustyczne</a></li>
                <li><a href="gitary_elektryczne.php">Gitary elektryczne</a></li>
            </ul>
            <a href="login.php" class="login_button"><span id="login_button_napis">Zaloguj się</span></a>
        </nav>
    </header>
    <main id="str_kont">
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="kontakt_form">
        <h1 class="contact_name">Napisz do nas!</h1>
        <label for="email">E-Mail:</label>
        <input type="email" name="email" class="email" required placeholder="jan.kowalski@gmail.com">
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




