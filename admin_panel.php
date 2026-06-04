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
<body id="admin_body">
    <header class="menu" style="width:100%">
        <nav >
            <a href="wyloguj.php" class="login_button"><span id="login_button_napis">Wyloguj się</span></a>
        </nav>
    </header>
    <section id="user_panel_content" style="width:97%">
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
            $productMessage = '';
            $productError = '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $price = $_POST['price'] ?? '';
                $img = trim($_POST['img'] ?? '');
                $type = (int)($_POST['type'] ?? 0);

                if ($name === '' || $description === '' || $img === '' || $price === '' || !in_array($type, [1, 2, 3], true)) {
                    $productError = 'Wszystkie pola są wymagane, a typ musi być 1, 2 lub 3.';
                } elseif (!is_numeric($price) || $price < 0) {
                    $productError = 'Cena musi być dodatnią liczbą.';
                } else {
                    $price = number_format((float)$price, 2, '.', '');
                    try {
                        $insertStmt = $pdo->prepare('INSERT INTO gitary (name, description, price, img, type) VALUES (?, ?, ?, ?, ?)');
                        $insertStmt->execute([$name, $description, $price, $img, $type]);
                        $productMessage = 'Produkt został dodany pomyślnie.';
                    } catch (PDOException $e) {
                        $productError = 'Błąd podczas dodawania produktu: ' . htmlspecialchars($e->getMessage());
                    }
                }
            }

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
    
    <section class="product_form">
        <?php if ($productError): ?>
            <p class="msg error"><?= htmlspecialchars($productError) ?></p>
        <?php elseif ($productMessage): ?>
            <p class="msg success"><?= htmlspecialchars($productMessage) ?></p>
        <?php endif; ?>
        <h1 id="napis_dodaj_produkt">Dodaj nowy produkt do katalogu gitar:</h1>
        <form method="post" action="admin_panel.php" id="add_product_form">
            <input type="hidden" name="add_product" value="1">
            <label for="name" class="add_label">Nazwa produktu:</label>
            <input type="text" id="name" name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
            <label for="description" class="add_label">Opis:</label>
            <textarea id="description" name="description" rows="4" required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
            <label for="price" class="add_label">Cena (PLN):</label>
            <input type="text" class="price" name="price" value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '' ?>" required>
            <label for="img" class="add_label">Ścieżka do zdjęcia (np. obrazy/gitara.jpg):</label>
            <input type="text" id="img" name="img" value="<?= isset($_POST['img']) ? htmlspecialchars($_POST['img']) : '' ?>" required>
            <label for="type" class="add_label">Typ gitary:</label>
            <select id="type" name="type" required>
                <option value="">Wybierz typ</option>
                <option value="1" <?= (isset($_POST['type']) && $_POST['type'] === '1') ? 'selected' : '' ?>>Klasyczna</option>
                <option value="2" <?= (isset($_POST['type']) && $_POST['type'] === '2') ? 'selected' : '' ?>>Akustyczna</option>
                <option value="3" <?= (isset($_POST['type']) && $_POST['type'] === '3') ? 'selected' : '' ?>>Elektryczna</option>
            </select>
            <button type="submit">Dodaj produkt</button>
        </form>
    </section>
    <footer style="width:100%">
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