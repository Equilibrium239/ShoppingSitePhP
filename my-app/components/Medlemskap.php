



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bli Medlem</title>
    <link rel="stylesheet" href="my-app/src/style.css">
    <style>

    body {
    margin: 0;
    padding: 0;
    display: flex;          
    justify-content: center; 
    align-items: center;     
    min-height: 100vh;      
    background-color: #f4f7f6; 
}



    .form-wrapper {
    background: gray;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 350px;
    margin: 20px;
}

.form-title {
   color: #333;
   text-align: center;
   margin-bottom: 25px;
   font-size: 1.4rem;
}


.form-label {
    display: block;
    font-weight: 600;
    color: #555;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-input {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 16px;
    transition: all 0.3s ease;
}


.form-input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 3px rgba(0, 123, 255, 0.1);
}


.btn-primary {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-bottom: 20px;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.form-footer {
    text-align: center;
    border-top: 1px solid #eee;
    padding-top: 15px;
    margin-top: 10px;
}

.form-footer p {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
}


.btn-link {
    color: #007bff;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
}

.btn-link:hover {
    text-decoration: underline;
}


.form-input {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px; 
    border: 1px solid #ddd;
    border-radius: 8px;
    box-sizing: border-box;
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

