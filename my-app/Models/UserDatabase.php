<?php
require_once '../../vendor/autoload.php';

class UserDatabase {
    private $pdo;
    private $auth;

    function __construct($pdo) {
        $this->pdo = $pdo;
        $this->auth = new \Delight\Auth\Auth($pdo);
    }

    function getAuth() {
        return $this->auth;
    }

    // Registrera ny användare
    function registerUser($username, $email, $password) {
        try {
            $this->auth->register($email, $password, $username);
            return ["success" => true, "message" => "Användare registrerad!"];
        } catch (\Delight\Auth\InvalidEmailException $e) {
            return ["success" => false, "message" => "Ogiltig e-postadress."];
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            return ["success" => false, "message" => "Ogiltigt lösenord."];
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            return ["success" => false, "message" => "Email redan registrerad."];
        }
    }

    // Logga in användare
    function loginUser($email, $password) {
        try {
            $this->auth->login($email, $password);
            return ["success" => true, "message" => "Inloggad!"];
        } catch (\Delight\Auth\InvalidEmailException $e) {
            return ["success" => false, "message" => "Ogiltig e-postadress."];
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            return ["success" => false, "message" => "Fel email eller lösenord."];
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            return ["success" => false, "message" => "E-posten är inte verifierad."];
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            return ["success" => false, "message" => "För många försök. Försök igen senare."];
        }
    }

    function setupUsers() {
    }

    function seedUsers() {
        if ($this->pdo->query("SELECT * FROM users WHERE email='stefan.holmberg@systementor.se'")->rowCount() == 0) {
            $userId = $this->auth->admin()->createUser(
                "stefan.holmberg@systementor.se",
                "Hejsan123#",
                "stefan.holmberg@systementor.se"
            );
        }
    }
}
?>