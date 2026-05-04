



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bli Medlem</title>
    <link rel="stylesheet" href="my-app/src/style.css">
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

        p {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #777;
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

      
        br {
            display: none;
        }
    </style>
</head>
<body>
    <div class="form-wrapper">
     <h2 class="form-title">Logga in om du är medlem</h2>

      <form action="Register.php" method="POST" class="auth-form">
        <label for="name" class="form-label">Namn:</label><br>
        <input type="text" id="name" name="name" class="form-input" required><br><br>

        <label for="email" class="form-label">Email:</label><br>
        <input type="email" id="email" name="email" class="form-input" required><br><br>

        <label for="password" class="form-label">Lösenord:</label><br>
        <input type="password" id="password" name="password" class="form-input" required><br><br>

        <button type="submit" class="btn-primary">Logga in</button>

        <div>
            <p>Inte medlem än?</p>
            <a href="Register.php" class="btn-link">Registrera dig här</a>
        </div>

      </form>
    </div>


    <?php 

$host = 'localhost';
$dbname = 'Shopping';
$user = 'root';
$password = 'root'; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
        die("❌ Fyll i alla fält");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "✅ Välkommen, $username! Du är nu medlem!";
    } else {
        echo "❌ Fel: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

?>


    
</body>
</html>

