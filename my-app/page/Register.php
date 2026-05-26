<?php
ob_start();
require_once('../../Utils/Validator.php');
require_once('../Models/Database.php');

$v = new Validator($_POST);

$database = new Database();
$email = "";
$password = "";
$passwordRepeat = "";
$name = "";
$streetaddress = "";
$postalCode = "";
$city = "";  

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['repeat_password'];
    $name = $_POST['name'];
    $streetaddress = $_POST['street'];
    $postalCode = $_POST['postal'];
    $city = $_POST['city'];

    $v->field('email')->required()->email();
    $v->field('password')->required()->min_len(8)->max_len(20);
    $v->field('repeat_password')->equals($password);
    $v->field('name')->required()->min_len(3)->max_len(50);
    $v->field('street')->required()->min_len(3)->max_len(50);
    $v->field('postal')->required()->max_len(10);
    $v->field('city')->required()->max_len(50);
    
    if ($v->is_valid()) {
        try {
            $userid = $database->getUsersDatabase()->getAuth()->register($email, $password, $email);
            $database->addUserDetails($userid, $name, $streetaddress, $postalCode, $city);
            header("Location: /AccountLogin.php");
            exit;
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $message = "❌ Användaren finns redan.";
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $message = "❌ Ogiltig email.";
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $message = "❌ För många försök, prova igen senare.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrera</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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

        .box h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 1.8rem;
        }

        .box input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .box input:focus {
            outline: none;
            border-color: #333;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .box button {
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

        .box button:hover {
            background-color: #555;
        }

        .msg-success { color: #059669; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 10px; border-radius: 6px; margin-top: 15px; }
        .msg-error   { color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; padding: 10px; border-radius: 6px; margin-top: 15px; }

        .error {
            color: #dc2626;
            font-size: 0.8rem;
            display: block;
            text-align: left;
            margin-bottom: 10px;
        }

        .login-link {
            display: block;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #666;
            text-decoration: none;
        }

        .login-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Registrera</h2>

        <form method="post">
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error"><?php echo $v->get_error_message('email'); ?></span>

            <input type="password" name="password" placeholder="Lösenord">
            <span class="error"><?php echo $v->get_error_message('password'); ?></span>

            <input type="password" name="repeat_password" placeholder="Upprepa lösenord">
            <span class="error"><?php echo $v->get_error_message('repeat_password'); ?></span>

            <input type="text" name="name" placeholder="Namn" value="<?php echo htmlspecialchars($name); ?>">
            <span class="error"><?php echo $v->get_error_message('name'); ?></span>

            <input type="text" name="street" placeholder="Gatuadress" value="<?php echo htmlspecialchars($streetaddress); ?>">
            <span class="error"><?php echo $v->get_error_message('street'); ?></span>

            <input type="text" name="postal" placeholder="Postnummer" value="<?php echo htmlspecialchars($postalCode); ?>">
            <span class="error"><?php echo $v->get_error_message('postal'); ?></span>

            <input type="text" name="city" placeholder="Stad" value="<?php echo htmlspecialchars($city); ?>">
            <span class="error"><?php echo $v->get_error_message('city'); ?></span>

            <button type="submit">Skapa konto</button>
        </form>

        <?php if ($message): ?>
            <div class="<?php echo str_contains($message, '❌') ? 'msg-error' : 'msg-success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <a class="login-link" href="/AccountLogin.php">Har du redan ett konto? Logga in här</a>
    </div>
</body>
</html>