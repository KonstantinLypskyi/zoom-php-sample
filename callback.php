<?php
require_once 'config.php';

function Redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
  
try {
    $client = new GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);
 
    $response = $client->request('POST', '/oauth/token', [
        "headers" => [
            "Authorization" => "Basic ". base64_encode(CLIENT_ID.':'.CLIENT_SECRET)
        ],
        'form_params' => [
            "grant_type" => "authorization_code",
            "code" => $_GET['code'],
            "redirect_uri" => REDIRECT_URI
        ],
    ]);
 
    $token = json_decode($response->getBody()->getContents(), true);
    $result = current($token);

    Redirect("https://zoom-php-app.herokuapp.com/create-meeting.php?token=$result");
 
    // echo json_encode($token);
    // echo "Access token inserted successfully.";
} catch(Exception $e) {
    echo $e->getMessage();
}