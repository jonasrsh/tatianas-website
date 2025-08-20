<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Honeypot prüfen
    if (!empty($_POST['website'])) {
        die("Spam erkannt!");
    }

    // Eingaben filtern
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    $message = htmlspecialchars($_POST['message']) : '';

    if (empty($name) || empty($email) || empty($message)) {
        exit("Bitte fülle alle Felder korrekt aus.");
    }

    // reCAPTCHA prüfen
    $recaptcha_secret = "6LdwwKsrAAAAAMgewG7WKqWnr3GRc8x8HOFbsgrP";
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";

    $data = [
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($recaptcha_url, false, $context);
    if ($result === FALSE) {
        exit("Fehler bei der reCAPTCHA-Anfrage.");
    }

    $responseKeys = json_decode($result, true);
    if (empty($responseKeys["success"]) || !$responseKeys["success"]) {
        exit("reCAPTCHA-Überprüfung fehlgeschlagen.");
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
