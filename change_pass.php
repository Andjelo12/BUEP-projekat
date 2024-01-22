<?php
session_start();
require_once "config.php";
require_once "functions_def.php";
$email = $_SESSION['username'];
$token = createToken(20);
if ($token) {
        setForgottenToken($pdo, $email, $token);
        $id_user = getUserData($pdo, 'id_user', 'email', $email);
        try {
            $body = "Da bi ste izmenili lozinku posetite <a href=" . SITE . "forget.php?token=$token>link</a>. Ovo je automatski generisana poruka. Ne odgovarajte na nju.";
            sendEmail($pdo, $email, $emailMessages['change'], $body, $id_user);
            $_SESSION['change_pass'] = 'Zahtev za promenu lozinke je poslat na vašu e-mail adresu.';
            redirection('edit_profile.php');
        }catch (PDOException $e)
        {
            exit("Error: " . $e->getMessage());
        }
}
