$host = 'localhost';
$dbname = 'siminski_strona';
$user = 'adminSiminski';
$pass = 'admin';

$dsn = 'mysql:host=$host;dbname=$dbname';

try{
    $pdo = new PDO($dsn, $user, $pass);
}   catch(PDOException $e){
    echo "Błąd połączenia: " . $e->getMessage()
}