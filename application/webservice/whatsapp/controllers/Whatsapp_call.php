<?php defined('BASEPATH') or exit('No direct script access allowed');
class Whatsapp_call extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('whatsapp_call_model');
    }
    public function index_get()
    {
        $get_data = $this->input->get();

        $success = 0;
        $message = "Something went wrong";
        $data = [];

		if(isset($get_data['environment']) && $get_data['environment'] != "" && isset($get_data['subject']) && $get_data['subject'] != ""){
			$params = $get_data;
			$whatsapp_number = ["+918381058482","+918485835691"];
			foreach ($whatsapp_number as $key => $value) {
				$this->callWhatsApp($params,$value);
			}
			
		}else{
			$message = "Parameter missing";
		}
        

        return  $this->response(array(
            "success" => $success,
            "message" => $message,
            'data' => $data
        ),  REST_Controller::HTTP_OK);
        
        
    }

    public function callWhatsApp($params = [],$number = ""){

		

		$url = "https://api.twilio.com/2010-04-01/Accounts/$TWILIO_ACCOUNT_SID/Messages.json";

		// Content variables array
		$contentVariables = [
			"1" => $params['environment'],
			"2" => $params['subject']
		];

		// Convert to JSON
		$contentVariablesJson = json_encode($contentVariables);

		// POST fields
		$postData = [
			"ContentSid" => "",           // Your approved template SID
			"To" => "whatsapp:{$number}",
			"From" => "whatsapp:+14155238886",
			"ContentVariables" => $contentVariablesJson
		];

		// Init cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $TWILIO_ACCOUNT_SID . ":" . $TWILIO_AUTH_TOKEN);

		// Execute
		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			echo "Curl Error: " . curl_error($ch);
		} else {
			echo "Response: " . $response;
		}

		curl_close($ch);

	}
   
}
