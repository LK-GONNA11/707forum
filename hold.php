<?php

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$messages = [
    'en' => [
        'title' => "SECURITY HOLD // 707FORUM",
        'header' => "SECURITY VERIFICATION IN PROGRESS",
        'message' => "Our systems have detected unusual activity from your connection.",
        'instruction' => "Please wait while we verify your network credentials.",
        'warning' => "This process is automatic. Do not refresh the page.",
        'footer' => "All connections are monitored and recorded for security purposes."
    ],
    'fr' => [
        'title' => "VÉRIFICATION DE SÉCURITÉ // 707FORUM",
        'header' => "VÉRIFICATION DE SÉCURITÉ EN COURS",
        'message' => "Nos systèmes ont détecté une activité inhabituelle depuis votre connexion.",
        'instruction' => "Veuillez patienter pendant la vérification de vos identifiants réseau.",
        'warning' => "Ce processus est automatique. Ne rafraîchissez pas la page.",
        'footer' => "Toutes les connexions sont surveillées et enregistrées à des fins de sécurité."
    ]
];
$msg = isset($messages[$lang]) ? $messages[$lang] : $messages['en'];
?>
