<?php
require(__DIR__ . '/vendor/autoload.php');
use juno_okyo\Chatfuel;
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set("Asia/Bangkok");
$result = array();
if (isset($_GET['city'])){
    $user_city = $_GET['city'];
    $user_country = $_GET['country'];
    $api_url = "http://api.openweathermap.org/data/2.5/weather?q=" . $user_city . "," . $user_country . "&appid=927fcc8790865ad9197e6ab30b525117";
    // http://api.openweathermap.org/data/2.5/weather?q=bhaktapur%2Cnepal&appid=927fcc8790865ad9197e6ab30b525117

    $response = getUrl($api_url);
    if ($response['content'] === FALSE) {
        if ($response['headers'][0] == "HTTP/1.1 404 Not Found") {
            echo "<code> City Not Found! Please try again.</code>";
        } else {
            echo "Error";
        }
    } else {
        $weather_data = file_get_contents($api_url);
        $json = json_decode($weather_data, TRUE);


        $result['user_temp'] = $json['main']['temp']; // Temperature. Default Unit : Kelvin
        $result['user_humidity'] = $json['main']['humidity']; // Humidity, %
        $result['user_conditions'] = $json['weather'][0]['description']; // weather condition
        $result['user_wind'] = $json['wind']['speed'];
        $result['city_name'] = $json['name'];
        $result['country_code'] = $json['sys']['country']; // Country code (GB, JP etc.)
        $result['sunrise'] = $json['sys']['sunrise']; // Sunrise time, unix, UTC
        $result['sunset'] = $json['sys']['sunset']; // Sunrise time, unix, UTC

        (new Chatfuel())->sendText("Temperature in ".$_GET['city']." is ".$json['main']['temp']);
    }
}else{
    $result['status'] = 'Invalid Parameters';
}
//header('Content-type: application/json');
//echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>

<?php
function getUrl($url) {
    try {
        $content = file_get_contents($url);
        return array('headers' => $http_response_header, 'content' => $content);
    }catch(Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }
}

function kelvin_to_celsius($kelvin) {
    $celsius = $kelvin - 273.15;
    return $celsius;
}
?>
