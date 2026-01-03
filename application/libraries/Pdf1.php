<?php
require_once APPPATH . 'libraries/tcpdf/tcpdf.php';

class PDF1 extends TCPDF {
	// pr("ok",1);
    // Page header
	 
    public function Header() {
        // Set font for the header
        $this->SetFont('helvetica', '', 10);


        // Set the background color
        $this->SetFillColor(255, 255, 255);
        if($this->header_y_axix > 0){
            $this->SetY($this->header_y_axix); 
        }else{
           $this->SetY(2.3); 
        }
        
        // Define the header table structure
//         $table = '

//         <table cellspacing="0" cellpadding="4" border="1">
//                     <tr>
//                         <td width="20%" style="text-align:center;font-size:14.8px;vertical-align:middle" rowspan="3">&nbsp;&nbsp;<br><b>TEST TECHNOPLAST</b></td>
//                         <td width="60%" style="text-align:center;font-size:14.8px;" rowspan="3">&nbsp;&nbsp;<br><b>INWARD INSPECTION REPORT</b></td>
//                         <td width="20%" style="text-align:left;font-size:14.8px;" ><b>&nbsp;&nbsp;Format No : QA-F-01</b></td>
//                     </tr>
//                     <tr>
//                         <td width="20%" style="text-align:left;font-size:14.8px;" ><b>&nbsp;&nbsp;Rev No : 00</b></td>
//                     </tr>
//                     <tr>
//                         <td width="20%" style="text-align:left;font-size:14.8px;" ><b>&nbsp;&nbsp;Rev Dt. : 3/3/2017</b></td>
//                     </tr>
                    
//                   </table>
//        			 <table cellspacing="0" cellpadding="6" border="1">
//                     <tr>
//                         <td width="20%" style="text-align:center;padding:20px;font-size:15px;" >Supplier Name</td>
//                         <td width="26%" style="text-align:center;font-size:15px;">RJ printing</td>
//                         <td width="18%" style="text-align:center;font-size:15px;">Part No & Discp</td>
//                         <td width="36%" style="text-align:center;font-size:15px;padding:20px;" colspan="3">asp-1ak / bush-1</td>
                        
//                     </tr>
//                     <tr>
//                         <td width="20%" style="text-align:center;padding:20px;font-size:15px;" >Invoice No </td>
//                         <td width="26%" style="text-align:center;font-size:15px;">Test1243</td>
//                         <td width="18%" style="text-align:center;font-size:15px;">Invoice Date</td>
//                         <td width="18%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">2024-07-09</td>
//                         <td width="9%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">Lot Qty</td>
//                         <td width="9%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">12</td>
                        
//                     </tr>
//                     <tr>
//                         <td width="20%" style="text-align:center;padding:20px;font-size:15px;" >Material Garde  </td>
//                         <td width="26%" style="text-align:center;font-size:15px;">A</td>
//                         <td width="18%" style="text-align:center;font-size:15px;">Inspection Date</td>
//                         <td width="18%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">2024-07-09</td>
//                         <td width="9%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">Rej Qty</td>
//                         <td width="9%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">12</td>
                        
//                     </tr>	
                    
//                   </table>
//                   <table cellspacing="0" cellpadding="6" border="1">
                    
//                     <tr>
//                         <td width="7%" style="text-align:center;padding:20px;font-size:15px;" >SR NO </td>
//                         <td width="13%" style="text-align:center;padding:20px;font-size:15px;" >PARAMETER </td>
//                         <td width="13%" style="text-align:center;font-size:15px;">SPECIFICATION</td>
//                         <td width="13%" style="text-align:center;font-size:15px;">MEASUREMENT
// TECHNIQUE </td>
//                         <td width="9%" style="text-align:center;font-size:15px;">OBV-1</td>
//                         <td width="9%" style="text-align:center;font-size:15px;">OBV-2</td>
//                         <td width="9%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">OBV-3</td>
//                         <td width="9%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">OBV-4</td>
//                         <td width="9%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">OBV-5</td>
//                         <td width="9%" style="text-align:center;padding:20px;font-size:15px;" colspan="2">REMARKS</td>
                        
//                     </tr>
                      
                    
//                   </table>';

        // Position at 15 mm from top
        // $this->SetY(15);
        // Print table in the header
        $this->writeHTML($this->header_html, true, false, false, false, '');
    }

    // Page footer
    public function Footer() {
        // Set position of the footer at 15 mm from bottom
        
        if($this->footer_y_axix != -24.8){
            $this->SetY($this->footer_y_axix); 
        }else{
           $this->SetY(-24.8);
        }
        // Set font for the footer
        // $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $table = '
        // <table cellspacing="0" cellpadding="8" border="1">
        //             <tr>
        //                 <td width="40%" style="text-align:left;font-size:14.8px;" >Sampling Plan : As per IS0 2500</td>
        //                <td width="60%" style="text-align:left;font-size:14.8px;" >Sampling Qty :</td>
        //             </tr>
        //             <tr>
        //                 <td width="100%" style="text-align:left;font-size:14.8px;" ><b>&nbsp;&nbsp;Inspected By :</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Verified By :</b></td>

        //             </tr>
                    
        //           </table>';
        $this->writeHTML($this->footer_html, true, false, false, false, '');
    }
}