<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Honeypot
    if (!empty($_POST['website'])) {
        exit("Spam erkannt!");
    }

    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if (!$email) {
        exit("Ungültige E-Mail-Adresse.");
    }

    $to = "DEINE_EMAIL@domain.ch";
    $subject = "Neue Nachricht von $name";
    $body = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message";

    if (mail($to, $subject, $body, "From: $email")) {
        echo "Nachricht erfolgreich gesendet!";
    } else {
        echo "Fehler beim Senden!";
    }
}
?>
