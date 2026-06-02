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
            $userName = $_SESSION['user_name'] ?? 'Użytkownik';
            $messages = [];
            $error = '';
            if (isset($_GET['delete'])) {
                $deleteId = (int)$_GET['delete'];
                try {
                    $deleteStmt = $pdo->prepare('DELETE FROM wiadomosci WHERE id = ? AND email = ?');
                    $deleteStmt->execute([$deleteId, $userEmail]);
                    header('Location: user_panel.php');
                    exit;
                } catch (PDOException $e) {
                    $error = 'Błąd podczas usuwania: ' . htmlspecialchars($e->getMessage());
                }
            }
            try {
                $stmt = $pdo->prepare('SELECT message, id FROM wiadomosci WHERE email = ? ORDER BY id DESC');
                $stmt->execute([$userEmail]);
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $error = 'Błąd pobierania wiadomości: ' . htmlspecialchars($e->getMessage());
            }
        ?>
        <h1>Witaj, <?= htmlspecialchars($userName) ?></h1>
            <p>Wyświetlone wiadomości zostały wysłane z Twojego adresu e-mail: <strong><?= htmlspecialchars($userEmail) ?></strong></p>
        <?php if ($error): ?>
            <p class="msg"><?= $error ?></p>
        <?php endif; ?>
        <?php if (count($messages) === 0): ?>
            <p>Nie znaleziono żadnych wiadomości dla Twojego konta.</p>
        <?php else: ?>
            <div class="tabela_wiaodmosci">
                <div id="tabela_wiadomosci_naglowek">
                    <p>Treść wiadomości:</p>
                </div>
                <div id="tabela_wiadomosci_tresc">
                    <?php foreach ($messages as $message): ?>
                        <article class="wiadomosc">
                            <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                            <a class="delete" title="Usuń wiadomość" href="admin_panel.php?delete=<?=$message['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć tę wiadomość?');">Usuń</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
    </body>
</html>