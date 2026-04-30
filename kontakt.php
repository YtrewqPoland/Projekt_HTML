require "db-connection.php"

<form action="<?= htmlspercialchars($_SERVER['PHP_SELF']) ?>" method="post", class="contact_form">
    <label for="email"E-Mail:></label>
    <input type="email" name="email" id="email" required placeholder="jan.kowalski@gmail.com">
    <textarea name="message" id="message"></textarea>
    <input type="submit" value="Wyślij" name="submit" id="submit">
</form>

$info = '';
$info_success = '';
if ($_SERVER["REQUEST_METHOD"] ==="POST"){
    $email = $_POST['email'];
    $message = $_POST['message'];
    try{
        $sql = "INSERT INTO wiadomosci (email, message) VALUES (?, ?)"
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $message]);
        $infoSuccess = "Twoja wiadomość została wysłana";
    }catch (PDOException $e){
        $info = "Coś poszło nie tak. Spróbuj ponownie.";
    }
}