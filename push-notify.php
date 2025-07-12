<?php
function sendPushNotification($title, $message, $userId = null) {
    $appId = "YOUR_ONESIGNAL_APP_ID"; // 🔁 Replace with your real OneSignal App ID
    $restApiKey = "YOUR_REST_API_KEY"; // 🔁 Replace with your REST API key

    $content = array(
        "en" => $message
    );

    $heading = array(
        "en" => $title
    );

    $fields = array(
        'app_id' => $appId,
        'headings' => $heading,
        'contents' => $content,
        'included_segments' => array('All') // You can filter by tag or player_id for specific users
    );

    $fields = json_encode($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic ' . $restApiKey
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
?>
