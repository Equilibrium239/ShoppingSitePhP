<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="my-app/src/style.css">
</head>
<style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 1.8rem;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #333;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #555;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            font-size: 0.9rem;
            border-radius: 6px;
        }

        
        .msg-success { color: #059669; background: #ecfdf5; border: 1px solid #a7f3d0; }
        .msg-error { color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; }

        .login-link {
            display: block;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #666;
            text-decoration: none;
        }

        .login-link:hover { text-decoration: underline; }
    </style>
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
$host = "localhost";
$dbname = "medlemskap";
$user = "root";
$pass = "";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

   
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    if (strlen($username) < 3) {
        $message = "❌ Användarnamn måste vara minst 3 tecken.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Ogiltig email.";
    } elseif (strlen($password) < 6) {
        $message = "❌ Lösenord måste vara minst 6 tecken.";
    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "❌ Email finns redan.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $message = " Registrering lyckades!";
            } else {
                $message = " Något gick fel.";
            }

            $stmt->close();
        }

        $check->close();
    }

    $conn->close();
}
?>
    
    <div class="message">
      <?php echo $message; ?>
    </div>
    
</body>
</html>