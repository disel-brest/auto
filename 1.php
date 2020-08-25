<?php
/*$lang = 0; // russian
$headerOptions = array(
    'http' => array(
        'method' => "GET",
        'header' => "Accept-language: en\r\n" .
            "Cookie: remixlang=$lang\r\n"
    )
);
$methodUrl = 'http://api.vk.com/method/database.getCountries?v=5.5&need_all=1&count=1000';
$streamContext = stream_context_create($headerOptions);
$json = file_get_contents($methodUrl, false, $streamContext);
$arr = json_decode($json, true);
echo 'Total countries count: ' . $arr['response']['count'] . ' loaded: ' . count($arr['response']['items']);
print_r($arr['response']['items']);*/

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="/favicon.ico">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
</head>
<body>
<script type="text/javascript">
    
</script>
</body>
</html>