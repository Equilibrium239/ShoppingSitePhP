<?php

class UserDatabase {
    private $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Registrera ny användare
    function registerUser($username, $email, $password) {
        // Kolla om email redan finns
        $check = $this->pdo->prepare("SELECT id FROM Users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            return ["success" => false, "message" => "Email redan registrerad."];
        }

        $sql = "INSERT INTO Users (username, email, password) VALUES (?, ?, ?)";
        $query = $this->pdo->prepare($sql);
        $query->execute([$username, $email, $password]);

        return ["success" => true, "message" => "Användare registrerad!"];
    }

    // Logga in användare
    function loginUser($email, $password) {
        $query = $this->pdo->prepare("SELECT * FROM Users WHERE email = ? AND password = ?");
        $query->execute([$email, $password]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return ["success" => true, "user" => $user];
        } else {
            return ["success" => false, "message" => "Fel email eller lösenord."];
        }
    }
}
?>