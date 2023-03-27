<?php
// app_token
$app_token="YeQV8cZmucYQF3WCRTXaooG853JWKrdV1YTV6wid";
// user_token
$user_token="z3OG0VFKZYueCCbXiK0btBnJIj0ElsAVBkAidVJU";

// API接口
$url = "http://127.0.0.1:8000/glpi/apirest.php";

// 获取请求内容,如果是GET请求，则需要将请求参数解析为JSON对象
// 如果是POST请求,则直接使用file_get_contents('php://input')获取请求内容。
// 然后将请求内容作为POST请求的body，发送到另一个API接口。
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $input = [];
    parse_str($_SERVER['QUERY_STRING'], $input);
    $requestJson = json_encode(array("input" => $input));
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestBody = file_get_contents('php://input');
    if (empty($requestBody)) {
        echo "请求内容不能为空\n";
        return;
    }
    $requestJson = $requestBody;
} else {
    echo "不支持的请求方法\n";
    return;
}
$json = $requestJson;

// 获取缓存token
$token = "";
if (file_exists("session_token")) {
    $file = file_get_contents("session_token");
    $file = json_decode($file, true);
    if ((time() - $file["time"]) < 3600) {
        $token = $file["session_token"];
    }
}
if (empty($token)) {
    // 缓存不存在或者过期则重新获取
    $token = file_get_contents("$url/initSession?user_token=$user_token&app_token=$app_token");
    $token = json_decode($token, true);
    if ($token["session_token"] != 0 && !empty($token["session_token"])) {
        $token = $token["session_token"];
        $file = array("session_token" => $token, "time" => time());
        file_put_contents("session_token", json_encode($file));
    } else {
        echo "获取token失败:" . $token["errmsg"] . "\n";
        return;
    }
}

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "$url/ticket/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $json,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "Content-Type: application/json",
        "App-Token:$app_token",
        "Session-token:$token"
    ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
if ($err) {
    $response = curl_exec($curl);
    $err = curl_error($curl);
    if ($err) {
        $response = curl_exec($curl);
        $err = curl_error($curl);
    }
}
echo $response . "\n";
curl_close($curl);
