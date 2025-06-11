<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json");

  $guildid = $_POST['guildid'] ?? null;
  $userid = $_POST['userid'] ?? null;
  $roleid = $_POST['roleid'] ?? null;

  if (!$guildid || !$userid || !$roleid) {
      http_response_code(400);
      echo json_encode(["error" => "Parametri mancanti"]);
      exit;
  }

$botToken = $_POST['id'];
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
  echo $response;
?>
