<?php
require_once 'config.php';

function getUrlQuery($url, $key = null)
{
	$parts = parse_url($url); 
	if (!empty($parts['query'])) {
		parse_str($parts['query'], $query); 
		if (is_null($key)) {
			return $query;
		} elseif (isset($query[$key])) {
			return $query[$key];
		}        
	}
 
	return false;
}

function Redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
        header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
 
function create_meeting() {
    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $client = new GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);
 
    $accessToken = getUrlQuery($url, 'token');
 
    try {
        $response = $client->request('POST', '/v2/users/me/meetings', [
            "headers" => [
                "Authorization" => "Bearer $accessToken"
            ],
            'json' => [
                "topic" => "What's up zoom?",
                "type" => 2,
                "start_time" => "2020-05-05T20:30:00",
                "duration" => "30", // 30 mins
                "password" => "123456"
            ],
        ]);
 
        $data = json_decode($response->getBody());
        echo '<h1>Start URL for expert (desktop or web):</h1>'. $data->start_url;
        echo "<br /> <h1>Join URL for participant (desktop or web): </h1>". $data->join_url;
        echo '<br /> <h1>Join URL for participant (web only): </h1>'. str_replace('/j/', '/wc/join/', $data->join_url);
 
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}
 
create_meeting();