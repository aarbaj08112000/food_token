<?php defined('BASEPATH') or exit('No direct script access allowed');
class Token_generate extends My_Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('token_generate_model');
    }
    public function index_post()
    {
        
        if ($this->authenticate() !== true) {
            return;
        }
        $user_id = $this->current_user->user_id;
        $restaurant_id = $this->current_user->restaurant_id;
        $post_data = $this->input->post();
        if(!(count($post_data) > 0)){
            $data = json_decode($this->input->raw_input_stream, true);
            $_POST = $data;
        }
        
        $config = array(
            array(
                'field' => 'items',
                'label' => 'Items',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        $items = $this->security->xss_clean($this->input->post("items"));
        $success = 0;
        $message = "Something went wrong";
        $data = [];
        
        if(count($items) > 0){
            $items_ids = array_column($items,"id");
            $items_data = $this->token_generate_model->get_items($items_ids,$restaurant_id);
            $items_name_data = array_column($items_data,"name","item_id");
            $items_data = array_column($items_data,"base_price","item_id");
            $insert_items = [];
            $total_amount = 0;
            $print_items = [];
            foreach ($items as $key => $value) {
                $price_val = $items_data[$value['id']] > 0 ? $items_data[$value['id']] : 0;
                $insert_items[] = [
                    "item_id" => $value['id'],
                    "qty" => $value['qty'],
                    "price" => $price_val,
                ];
                $name_val = $items_name_data[$value['id']] != "" ? $items_name_data[$value['id']] : "";
                $print_items[] = [
                    "item_id" => $value['id'],
                    "qty" =>  number_format($value['qty']),
                    "price" =>  number_format($price_val,2),
                    "name" => $name_val
                ];
                $total_amount += $value['qty'] * $price_val;
            }
            $payload = [
                "restaurant_id" => $restaurant_id,
                "token_date" => date("Y-m-d"),
                "token_time" => date("H:i:s"),
                "added_by" => $user_id,
                "added_date" => date("Y-m-d H:i:s"),
                "items" => $insert_items
            ];
            $token_data = $this->token_generate_model->generate_token($payload);
            if($token_data['token_id'] > 0 ){
                $tokens_details = $this->token_generate_model->get_tokens_details($restaurant_id,$token_data['token_id']);
                $tokens_details['items'] = $print_items;
                $tokens_details['total_amount'] =  number_format($total_amount,2);
                $tokens_details['date_time'] = getDefaultDateTimeForToken($tokens_details['token_date']." ".$tokens_details['token_time']);
                $pdf_url = $this->print_token($tokens_details);
                
                $success = 1;
                $message = "Token generated sucessfully";
                $data = [
                    "token_number" => $token_data['token_number'],
                    "url" => $pdf_url
                ];
            }
        }else{
            $message = "Please add item for token";
        }

        return  $this->response(array(
            "success" => $success,
            "message" => $message,
            'data' => $data
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
