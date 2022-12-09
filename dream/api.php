<?


function get_code(){
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, "https://kabinet.dreamkas.ru/api/oauth2/authorize?client_id=&redirect_uri=");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	
	$response = curl_exec($ch);
	curl_close($ch);
	
	var_dump($response);
	//данные вернуться по url  пример Location:http://localhost/?uid=123&code=1234567890
}


function get_token(){
	$ch = curl_init();
//code - берется из get_code
	curl_setopt($ch, CURLOPT_URL, "https://kabinet.dreamkas.ru/api/oauth2/access_token");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	
	curl_setopt($ch, CURLOPT_POST, TRUE);
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{
	  \"code\": \"1234567890\",
	  \"client_id\": 1,
	  \"client_secret\": \"0aa9e134-b2ed-451e-bb98-c7d91ee843f2\"
	}");
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json"
	));
	
	$response = curl_exec($ch);
	curl_close($ch);
	
	var_dump($response);
	
}


function get_goods(){
	global $token;
	$ch = curl_init();
	 $authorization = "Authorization: Bearer ".$token; // **Prepare Autorisation Token**
     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // **Inject Token into Header**
	curl_setopt($ch, CURLOPT_URL, "https://kabinet.dreamkas.ru/api/products?limit=10&offset=0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	
	$response = curl_exec($ch);
	curl_close($ch);
	
	var_dump($response);	
	
}

function send_check($data){
	global $token, $id_kassa;	
		
	$ch = curl_init();
	$authorization = "Authorization: Bearer ".$token; // **Prepare Autorisation Token**
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // **Inject Token into Header**
	curl_setopt($ch, CURLOPT_URL, "https://kabinet.dreamkas.ru/api/receipts");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	
	curl_setopt($ch, CURLOPT_POST, TRUE);
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	
	
	
	$response = curl_exec($ch);
	curl_close($ch);
	$answer = json_decode( $response);
	
	var_dump($answer);
	return $answer->id;	
}


function get_operation($id){
	global $token, $id_kassa;
	$ch = curl_init();
	$authorization = "Authorization: Bearer ".$token; // **Prepare Autorisation Token**

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // **Inject Token into Header**
	curl_setopt($ch, CURLOPT_URL, "https://kabinet.dreamkas.ru/api/operations/".$id);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	
	$response = curl_exec($ch);
	curl_close($ch);
	$answer = json_decode( $response);
	
	var_dump($answer);
	return $answer->status;
}
?>