<?php
  /*header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json");

  $guildid = $_POST['guildid'] ?? null;
  $userid = $_POST['userid'] ?? null;
  $roleid = $_POST['roleid'] ?? null;

  if (!$guildid || !$userid || !$roleid) {
      http_response_code(400);
      echo json_encode(["error" => "Parametri mancanti"]);
      exit;
  }

$botToken = getenv('BOT_TOKEN');
  if (!$botToken) {
      http_response_code(500);
      echo json_encode(["error" => "Token bot non configurato"]);
      exit;
  }

  $url = "https://discord.com/api/v10/guilds/$guildid/members/$userid/roles/$roleid";

  $ch = curl_init();
  curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_HTTPHEADER => [
          "Authorization: Bot $botToken",
          "Content-Length: 0"
      ],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "PUT",
      CURLOPT_SSL_VERIFYPEER => false
  ]);

  $response = curl_exec($ch);
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  http_response_code($httpcode);
  echo $response;*/


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$guildid = $_GET['guildid'] ?? null;
$userid = $_GET['userid'] ?? null;
$roleid = $_GET['roleid'] ?? null;

$chaf = $_GET['ChAf'];
$chafConfronto = ((int)$guildid + (int)$userid + (int)$roleid)/100;
  
if ($chaf!=$chafConfronto) {
    echo 'non autorizzato';
    exit;
}

if (!$guildid || !$userid || !$roleid) {
    http_response_code(400);
    echo json_encode(["error" => "Parametri mancanti"]);
    exit;
}

$botToken = getenv('BOT_TOKEN');
if (!$botToken) {
    http_response_code(500);
    echo json_encode(["error" => "Token bot non configurato"]);
    exit;
}

// Aggiungi ruolo
$roleUrl = "https://discord.com/api/v10/guilds/$guildid/members/$userid/roles/$roleid";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $roleUrl,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bot $botToken",
        "Content-Length: 0"
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_SSL_VERIFYPEER => false
]);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode >= 200 && $httpcode < 300) {
    // Prendi info utente
    $userUrl = "https://discord.com/api/v10/users/$userid";

    $ch2 = curl_init();
    curl_setopt_array($ch2, [
        CURLOPT_URL => $userUrl,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bot $botToken"
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $userResponse = curl_exec($ch2);
    $userCode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);

    if ($userCode == 200) {
        $userData = json_decode($userResponse, true);
        $username = $userData['username'] ?? 'sconosciuto';
        $discriminator = $userData['discriminator'] ?? '0000';
        $tag = $username;

        // Redireziona con username nel GET
        header("Location: https://" . getenv('DOMAIN') . "/settings.php?username=" . urlencode($tag) . "&userid=" . $userid);
        exit;
    } else {
        echo json_encode(["error" => "Impossibile recuperare utente", "dettaglio" => $userResponse]);
    }
} else {
    echo json_encode(["error" => "Impossibile assegnare ruolo", "response" => $response]);
}
?>
