<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Honeypot prüfen
    if (!empty($_POST['website'])) {
        die("Spam erkannt!");
    }

    // Eingaben filtern
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    // reCAPTCHA prüfen
    $recaptcha_secret = "6LdwwKsrAAAAAMgewG7WKqWnr3GRc8x8HOFbsgrP";
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response"
    );
    $responseKeys = json_decode($response, true);
    if (empty($responseKeys["success"]) || !$responseKeys["success"]) {
        die("reCAPTCHA-Überprüfung fehlgeschlagen.");
    }

    // Mail senden
    if ($email) {
        $to = "mail@tatianakaufmann.art";
        $subject = "Neue Nachricht von $name";
        $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=utf-8";
        $body = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message";

        if (mail($to, $subject, $body, $headers)) {
            echo "Danke für deine Nachricht!";
        } else {
            echo "Fehler beim Senden der Nachricht.";
        }
    } else {
        echo "Ungültige E-Mail-Adresse!";
    }
} else {
    echo "Ungültige Anfrage!";
}
?>
