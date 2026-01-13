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
                $total = 123;
                $html = '<table width="100%" cellpadding="2">
                <tr>
                    <td><b style="font-size:13px;">TOTAL</b></td>
                    <td align="right"><b style="font-size:13px;">'.number_format($total, 2).'</b></td>
                </tr>
            </table>

            <hr>

            <div style="text-align:center;font-size:11px;">
                Thank you! Please visit again.
            </div>
            ';
                $data = [
                    "token_number" => $token_data['token_number'],
                    "url" => $pdf_url,
                    "html" => $html
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
		$pdf = new Pdf1('P', 'mm', array(58, 200), true, 'UTF-8', false);

		$pdf->SetMargins(0, 0, 0);
		$pdf->SetAutoPageBreak(true, 2);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		$pdf->AddPage();
        $pdf->setImageScale(1);
		
		// Font
		$pdf->SetFont('helvetica', '', 20);

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
            <div style="text-align:center;">
                <b style="font-size:14px;">'.$hotel_name.'</b><br>
                <span style="font-size:10px;">'.$address.'</span><br>
                <span style="font-size:10px;">'.$mobile.'</span>
            </div>

            <hr>

            <table width="58mm" cellpadding="2">
                <tr>
                    <td><b>Token:</b></td>
                    <td align="right"><b>'.$token_no.'</b></td>
                </tr>
                <tr>
                    <td><b>Date:</b></td>
                    <td align="right">'.$date_time.'</td>
                </tr>
            </table>

            <hr>

            <table width="58mm" cellpadding="2">
                <tr>
                    <th align="left" width="55%">Item</th>
                    <th align="center" width="15%">Qty</th>
                    <th align="right" width="30%">Amt</th>
                </tr>
            </table>

            <hr>

            <table width="58mm" cellpadding="2">
            ';

            foreach ($items as $item) {
                $html .= '
                <tr>
                    <td width="55%" style="font-size:11px;">'.$item['name'].'</td>
                    <td width="15%" align="center" style="font-size:11px;">'.$item['qty'].'</td>
                    <td width="30%" align="right" style="font-size:11px;">'.number_format($item['price'], 2).'</td>
                </tr>';
            }

            $html .= '
            </table>

            <hr>

            <table width="58mm" cellpadding="2">
                <tr>
                    <td><b style="font-size:13px;">TOTAL</b></td>
                    <td align="right"><b style="font-size:13px;">'.number_format($total, 2).'</b></td>
                </tr>
            </table>

            <hr>

            <div style="text-align:center;font-size:11px;">
                Thank you! Please visit again.
            </div>

            <div style="text-align:center;font-size:9px;">
                Code Crafter Infotech
            </div>
            ';
        $html = '<table width="100%" cellpadding="2">
                <tr>
                    <td><b style="font-size:13px;">TOTAL</b></td>
                    <td align="right"><b style="font-size:13px;">'.number_format($total, 2).'</b></td>
                </tr>
            </table>

            <hr>

            <div style="text-align:center;font-size:11px;">
                Thank you! Please visit again.
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
