<?php
ob_start();
require_once('../Models/Database.php');
require_once('../Models/UserDatabase.php');

$database = new Database();


$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    try {
        $database->getUsersDatabase()->getAuth()->login($email, $password);
        header("Location: /");
        exit;
    } catch (\Delight\Auth\InvalidEmailException $e) {
        $message = "❌ Fel användarnamn eller lösenord";
    } catch (\Delight\Auth\InvalidPasswordException $e) {
        $message = "❌ Fel användarnamn eller lösenord";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logga in</title>
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

        .form-wrapper {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .form-title {
            margin-bottom: 30px;
            color: #333;
            font-size: 1.6rem;
            font-weight: 600;
        }

        .form-label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #666;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #333;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .btn-primary {
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
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background-color: #555;
        }

        .btn-link {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        p {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #777;
        }

        .msg-error {
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fecaca;
            padding: 10px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="form-wrapper">
        <h2 class="form-title">Logga in</h2>

        <form method="POST">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-input" placeholder="Din email" required>

            <label for="password" class="form-label">Lösenord:</label>
            <input type="password" id="password" name="password" class="form-input" placeholder="Ditt lösenord" required>

            <button type="submit" class="btn-primary">Logga in</button>

            <?php if ($message): ?>
                <div class="msg-error"><?php echo $message; ?></div>
            <?php endif; ?>

            <div>
                <p>Inte medlem än?</p>
                <a href="Register.php" class="btn-link">Registrera dig här</a>
            </div>
        </form>
    </div>
</body>
</html>