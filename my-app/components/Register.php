<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <div class="box">
        <h2>Registrera</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Namn" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <input type="password" name="password" placeholder="Lösenord" required><br><br>

            <button type="submit">Skapa konto</button>
        </form>
    </div>

    
    <?php 
        
    $host = 'localhost';
    $dbname = 'Shopping';
    $user = 'root';
    $pass = '';

    $message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = new mysqli($host, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Connection failed" . $conn->connect_error);

        }
    }

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (strlen($username) < 3) {
        $message = "Användarnamn måste vara minst 3 tecken långt.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Ogiltig emailadress.";
    } elseif (strlen($password) < 6) {
        $message = "Lösenord måste vara minst 6 tecken långt.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Emailen är redan registrerad.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $message = "Registrering lyckades! Välkommen, " . $username . "!";
            } else {
                $message = "Ett fel uppstod:";
            }
            $stmt->close();
        }
        $stmt->close();
    }
    $stmt->close();
    
    ?>
    
    <div class="message">
      <?php echo $message; ?>
    </div>
    
</body>
</html>