<?php
require(__DIR__ . '/vendor/autoload.php');
require('countries_list.php'); 
use juno_okyo\Chatfuel;
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set("Asia/Bangkok");
$result = array();

if (isset($_GET['deshario_api'])){

    $user_city = $_GET['city'];
	
	$user_country = $_GET['country'] ? $_GET['country'] : "thailand";
	
	$user_country = manage_country($user_country,$user_city);
	
    $api_url = "http://api.openweathermap.org/data/2.5/weather?q=" . $user_city . "," . $user_country . "&appid=927fcc8790865ad9197e6ab30b525117";

    $response = getUrl($api_url);
    if ($response['content'] === FALSE) {
        if ($response['headers'][0] == "HTTP/1.1 404 Not Found") {
            $error = 'Oops! "'.$user_city.'" was not found.';
            (new Chatfuel())->sendText($error."\nPlease try again later.");
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
		
		$country = $json['sys']['country'];
		$temp = $json['main']['temp'];  
		$country_name = $countries_names[$country];
		
		$sunrise_time = $json['sys']['sunrise'];
		$sunset_time = $json['sys']['sunset'];
		
        $temp_data = "Temperature in ".ucfirst($user_city).",".ucfirst(strtolower($country_name))." is ".kelvin_to_celsius($temp)."°C.";
		  
        $sunrise_data = "Today in ".ucfirst($user_city).",".ucfirst(strtolower($country_name))." sunrise at ".getFullDate($sunrise_time);
		
        $sunset_data = "Today in ".ucfirst($user_city).",".ucfirst(strtolower($country_name))." sunset at ".getFullDate($sunset_time);
				

        if (isset($_GET['Dtemp'])){
            (new Chatfuel())->sendText($temp_data);
        }elseif (isset($_GET['Dsunrise'])){
            (new Chatfuel())->sendText($sunrise_data);
        }elseif (isset($_GET['Dsunset'])){
            (new Chatfuel())->sendText($sunset_data);
        }
		
    }
	
}else{
    echo "Request Fail !";
}

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
		return number_format($celsius);
	} 
	
	function getFullDate($timestamp){
		$timeFormat = 'H:i:s';
		$datetimeFormat = 'Y-m-d H:i:s';
		$date = new \DateTime(); 
		// $date = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
		$date->setTimestamp($timestamp);
		return $date->format($timeFormat);
	}
	  
	function manage_country($country,$city){
		if ($country == "nepal" || $country == "Nepal" || $city == "lalitpur"){
			$country = "NP";
		}
		if ($city == "california" || $country == "usa" || $country == "united states" || $country == "america"){
			$country = "US";
		}
		if ($country == "russia" || $city == "moscow"){
			$country = "RU";
		}
		return $country; 
	}

?>
