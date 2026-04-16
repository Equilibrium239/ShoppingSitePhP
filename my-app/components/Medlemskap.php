



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bli Medlem</title>
</head>
<body>
    <h2>bli Medlem</h2>

    <form action="register.php" method="POST">
        <label for="name">Namn:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Lösenord:</label><br>
        <input type="password" name="password" required><br><br>
    </form>


    <?php 

    $host = 'localhost';
    $dbname = 'Shopping';
    $user = 'root';
    $password = '';

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed" . $conn->connect_error);
    }

    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Välkommen, " . $username . "Du är nu en Medlem!";
    } else {
        echo "Ett fel uppstod" . $stmt->error;
    }


    $stmt->close();
    $conn->close();



?>



    
</body>
</html>

