<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administratora</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Life+Savers:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <header class="menu">
        <nav>
            <a href="wyloguj.php" class="login_button"><span id="login_button_napis">Wyloguj się</span></a>
        </nav>
    </header>
    <section id="user_panel_content">
        <?php
            session_start();
            require_once __DIR__ . '/db-connection.php';
            if (!isset($_SESSION['user_email'])) {
                header('Location: login.php');
                exit;
            }
            $userEmail = $_SESSION['user_email'];
            $userName = $_SESSION['user_name'] ?? 'Administrator';
            $messages = [];
            $error = '';
            if (isset($_GET['delete'])) {
                $deleteId = (int)$_GET['delete'];
                try {
                    // Admin może usuwać dowolną wiadomość po id
                    $deleteStmt = $pdo->prepare('DELETE FROM wiadomosci WHERE id = ?');
                    $deleteStmt->execute([$deleteId]);
                    header('Location: admin_panel.php');
                    exit;
                } catch (PDOException $e) {
                    $error = 'Błąd podczas usuwania: ' . htmlspecialchars($e->getMessage());
                }
            }
            try {
                // Pobierz wszystkie wiadomości z bazy (najpierw najnowsze)
                $stmt = $pdo->query('SELECT id, email, message FROM wiadomosci ORDER BY id DESC');
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $error = 'Błąd pobierania wiadomości: ' . htmlspecialchars($e->getMessage());
            }
        ?>
        <h1>Witaj, <?= htmlspecialchars($userName) ?></h1>
            <p>Poniżej wszystkie wiadomości z bazy:</p>
        <?php if ($error): ?>
            <p class="msg"><?= $error ?></p>
        <?php endif; ?>
        <?php if (count($messages) === 0): ?>
            <p>Nie znaleziono żadnych wiadomości dla Twojego konta.</p>
        <?php else: ?>
            <div class="tabela_wiaodmosci">
                <div id="tabela_wiadomosci_naglowek">
                    <p>Wiadomości:</p>
                </div>
                <div id="tabela_wiadomosci_tresc">
                    <?php foreach ($messages as $message): ?>
                        <article class="wiadomosc">
                            <p><strong>Od:</strong> <?= htmlspecialchars($message['email'] ?? 'Anonim') ?></p>
                            <p><?= nl2br(htmlspecialchars($message['message'] ?? '')) ?></p>
                            <a class="delete" title="Usuń wiadomość" href="admin_panel.php?delete=<?= $message['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć tę wiadomość?');">Usuń</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
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