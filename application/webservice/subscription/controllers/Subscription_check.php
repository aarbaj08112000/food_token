<?php defined('BASEPATH') or exit('No direct script access allowed');
class Subscription_check extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('subscription_model');
    }
    public function index_get()
    {
        
        if ($this->authenticate() !== true) {
            return;
        }
        $user_id = $this->current_user->user_id;
        $restaurant_id = $this->current_user->restaurant_id;
        $post_data = $this->input->post();

        $success = 0;
        $message = "Something went wrong";
        $data = [];
        
        $user_data = $this->subscription_model->get($user_id,$restaurant_id);
        $expiryDateTime = $user_data['subscription_valid_date']; // from DB

        $now = new DateTime(); 
        $expiry = new DateTime($expiryDateTime);

        // Calculate difference in days
        $diffSeconds = $expiry->getTimestamp() - $now->getTimestamp();
        $remainingDays = ceil($diffSeconds / 86400); // 86400 = seconds in 1 day

        $response = [
            'subscription_expired' => false,
            'subscription_days' => 0,
            'message' => ''
        ];

        // Case 1: Subscription expired
        if ($remainingDays <= 0) {

            $response['subscription_expired'] = true;
            $response['subscription_message_dispaly'] = true;
            $response['subscription_days'] = 0;
            $response['message'] = "Your subscription has expired";

        // Case 2: Expiring soon (<= 5 days)
        } elseif ($remainingDays <= 2) {

            $response['subscription_expired'] = false;
            $response['subscription_message_dispaly'] = true;
            $response['subscription_days'] = $remainingDays;
            $dayText = ($remainingDays == 1) ? 'day' : 'days';
            $response['message'] = "Your subscription is expiring soon in {$remainingDays} {$dayText}";

        // Case 3: Active (> 5 days)
        } elseif ($remainingDays <= 5) {

            $response['subscription_expired'] = false;
            $response['subscription_message_dispaly'] = true;
            $response['subscription_days'] = $remainingDays;
            $response['message'] = "Your subscription is expiring in {$remainingDays} days";
        }else{
            $response['subscription_expired'] = false;
            $response['subscription_message_dispaly'] = false;
            $response['subscription_days'] = $remainingDays;
            $response['message'] = "";
        }

        return  $this->response(array(
            "success" => !$response['subscription_expired'] ? 1 : 0,
            "message" => "Subscription details fetch successfully.",
            'data' => $response
        ), $success == 1 ? REST_Controller::HTTP_OK : REST_Controller::HTTP_NOT_FOUND);
        
        
    }

    public function print_token($token_details = [])
	{
		require_once APPPATH . 'libraries/Pdf1.php';
		$this->load->library('tcpdf');

		// Create PDF (Small receipt size)
		$pdf = new Pdf1('P', 'mm', array(64, 120), true, 'UTF-8', false);

		$pdf->SetMargins(3, 3, 3);
		$pdf->SetAutoPageBreak(true, 3);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		$pdf->AddPage();
		
		// Font
		$pdf->SetFont('helvetica', '', 8);

		// Sample dynamic data
		// $token_details = [
		// 	"restaurant_name" => "VEER VADA KAKA",
		// 	"address" => "G-5, JAIN HEIGHTS, A-WING\nSHOP NO 3, SALUNKHE NAGAR\nWANOWRIE, PUNE",
		// 	"mobile" => "Mob : 9850123456",
		// 	"token_no" => "105",
		// 	"date_time" => date('d/m/Y h:i A'),
		// 	"items" => [
		// 		['name' => 'KOLHAPURI MUTTON', 'qty' => 1, 'price' => 80],
		// 		['name' => 'SPAGHETTI BOLOGNESE', 'qty' => 1, 'price' => 70],
		// 	]
		// ];
		$hotel_name   = $token_details['restaurant_name'];
		$address      = $token_details['address'];
		$mobile       = $token_details['mobile'];
		$token_no     = $token_details['token_number'];
		$date_time    = $token_details['date_time'];
		$items = $token_details['items'];
		$total = $token_details['total_amount'];
        $restaurant_id = $token_details['restaurant_id'];
        // pr($token_no,1);
        // $token_no = 35;
		// HTML layout
		$html = '
		<div style="text-align:center;" cellpadding="2">
			<b>'.$hotel_name.'</b><br>
			<span style="font-size:7px;">'.$address.'</span><br>
			<span style="font-size:7px;">'.$mobile.'</span>
		</div>
		<div cellpadding="2" style="border-bottom:0.5px dashed #626567;line-height:2px;">&nbsp;</div>
		<table width="100%" cellpadding="2">
		<div cellpadding="0" style="line-height:1px;">&nbsp;</div>
			<tr>
				<td width="30%">Token No  &nbsp;:</td>
				<td >'.$token_no.'</td>
			</tr>
		</table>
		<table width="100%" cellpadding="2">
			<tr>
				<td width="30%">Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;:</td>
				<td width="70%">'.$date_time.'</td>
			</tr>
		</table>
		<div cellpadding="2" style="border-bottom:0.5px dashed #626567;line-height:4px;">&nbsp;</div>
		<table width="100%" cellpadding="2">
		<div cellpadding="0" style="line-height:1px;">&nbsp;</div>
			<tr>
				<th align="left" width="55%"><b>Item</b></th>
				<th align="center" width="15%"><b>Qty</b></th>
				<th align="right" width="30%"><b>Amt</b></th>
			</tr>
			<div cellpadding="2" style="line-height:0px;">&nbsp;</div>';

		foreach ($items as $item) {
			$html .= '
			<tr>
				<td style="font-size:7px;">'.$item['name'].'</td>
				<td align="center" style="font-size:7px;">'.$item['qty'].'</td>
				<td align="right" style="font-size:7px;">'.number_format($item['price'], 2).'</td>
			</tr>';
		}

		$html .= '
		</table>
		<div cellpadding="2" style="border-bottom:0.5px dashed #626567;line-height:4px;">&nbsp;</div>
		<table width="100%" cellpadding="2">
		<div cellpadding="2" style="line-height:0px;">&nbsp;</div>
			<tr>
				<td  width="20%"><b>Total</b></td>
				<td align="right" width="80%"><b>'.number_format($total, 2).'</b></td>
			</tr>
		</table>
		<br>
		<div style="text-align:center;font-size:7px;">
			Thank you! Please visit again.
		</div>
        <div cellpadding="8" style="line-height:1;text-align:center;font-size:6px;color:#626567;border-bottom:0.5px dashed #626567;border-top:0.5px dashed #626567;">
            <span style="line-height:12px;">&nbsp;</span>
			Design & Developed by Code Crafter Infotech <br> <span style="color:black;">www.codecrafterinfotech.com</span>
            <span style="line-height:12px;">&nbsp;</span>
		</div>
		';

		$pdf->writeHTML($html, true, false, true, false, '');

		// Output
        $folder_path = "public/uploads/token/".$restaurant_id;
		$upload_path = FCPATH.$folder_path;
		if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }
        $token_file = "/".$token_no.".pdf";
        // pr($token_file,1);
		$file_path = $upload_path.$token_file;
		$pdf->Output($file_path, 'F');
        $pdf_path = base_url($folder_path.$token_file);
        return $pdf_path;
	}
    
   
}
