<?php
require_once('tcpdf_include.php');

class top_bar{
	public $title;
	public $content;
	public function __construct($title,$content){
		$this->title = $title;
		$this->content = $content;
	}
	public function html_content(){
		$html = '<style>
			.header{
			}
			.header td{
				color: white;
			}
		</style>
		<table class="header" width="100%">
		 <tr nobr="true" width="100%">
		  <td width="30%" style="text-align:left">'.$this->title.'</td>
		  <td width="70%" style="text-align:right">'.$this->content.'</td>
		 </tr>
		</table>
		';
		return $html;
	}
}


// $fontname = TCPDF_FONTS::addTTFfont('./images/CircularStd-Medium.ttf', 'TrueTypeUnicode', '', 96); // tc
$fontname = TCPDF_FONTS::addTTFfont('./tcpdf/examples/images/CircularStd-Medium.ttf', 'TrueTypeUnicode', '', 96); // 
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 051');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);
$pdf->setPrintFooter(false);
$pdf->setPrintHeader(false);
$pdf->SetMargins(10, 12, 10);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont($fontname, '', 14, '', false);
$pdf->AddPage('P', 'A4');
$pdf->SetAutoPageBreak(false, 0);
$img_file = K_PATH_IMAGES.'background.png';
$title_img1 = K_PATH_IMAGES.'title_img1.png';
$botoom_right = K_PATH_IMAGES.'botoom_right.png';
$logo_img = K_PATH_IMAGES.'logo.png';
$pdf->Image($img_file, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($title_img1, 0, 0, 100, 80, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($logo_img, 70, 70, 70, 47, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($botoom_right, 150, 240, 60, 60, '', '', '', false, 300, '', false, false, 0);
$html = "
	<style>
		h1{
			text-align:center;
			color:white;
			font-size: 50px;
			font-family:'.$fontname'.;
			font-weight:bold;
		}
	</style>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<h1><b>Erichs Trip to Germany</b></h1>
";
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->AddPage('P', 'A4');
$top_bar_image = K_PATH_IMAGES.'top_bar.png';
$flight_image = K_PATH_IMAGES.'flight.png';
$car_image = K_PATH_IMAGES.'car.png';
$hotel_image = K_PATH_IMAGES.'hotel.png';
$dot_image = K_PATH_IMAGES.'dot_bar.png';
$dot_image1 = K_PATH_IMAGES.'dot_bar1.png';
$b_left = K_PATH_IMAGES.'bottom_left.png';
$b_left_text = K_PATH_IMAGES.'bottom_left_text.png';
$b_right = K_PATH_IMAGES.'bottom_right_img.png';
$dark_back = K_PATH_IMAGES.'dark_back.png';
$left_card = K_PATH_IMAGES.'left_card.png';
$right_card = K_PATH_IMAGES.'right_card.png';
$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
$section_2 = new top_bar('Your Itinerary','The travel plan of tomorrow done right today'); 
$html = $section_2->html_content();
$html .= '<div class="section">
		<br><br><br><br>
		<table class="body">
			<tr>
				<td width="10%">
					<div class="double" style="text-align:center;">
	   					<img src="'.$flight_image.'" width="40px" height="40px">	
					</div>
				</td>
				<td width="90%" rowspan="2">
					<p style="color:#0D256E; font-size:25px; font-weight:bold;">Flight Information</p>
			   		<table width="100%">
			   			<tr class="title">
			   				<td width="50%"><p style="color:#3E4754; font-size:14px;">Flight:LH5678/Seat 11C</p></td>
			   				<td width="50%"><p style="color:#F39F32; font-size:14px; text-align:right">Date:2021-05-15</p></td>
			   			</tr>
			   			<p style="width:100%; color:#fff; background-color:#fff; font-size:4px">e</p>
			   			<p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>
			   			<div></div>
			   			<tr class="content-1">
			   				<td width="80%"><p style="color:#67758D; font-size:14px"><b>Departure:</b> <span>Denver International Airport, Denver, Colorado, United States of America</span></p></td>
			   				<td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">14:30:00(MST)</p></td>
			   			</tr>
			   			<p style="width:100%; color:#fff; background-color:#fff; font-size:4px">e</p>
			   			<tr class="content-1">
			   				<td width="80%"><p style="color:#67758D; font-size:14px"><b>Departure:</b> <span>Denver International Airport, Denver, Colorado, United States of America</span></p></td>
			   				<td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">14:30:00(MST)</p></td>
			   			</tr>		   						   		
						<div></div>
			   		</table>
				</td>
			</tr>
			<tr height:calc(100% - 0px)>
				<td style="text-align:center;height:calc(100% - 0px)">
					<img src="'.$dot_image.'" width="2px" height="180px">
				</td>
			</tr>
		</table>		
	   	<table class="body">
			<tr>
				<td width="10%">
					<div class="double" style="text-align:center;">
	   					<img src="'.$car_image.'" width="40px" height="40px">	
					</div>
				</td>
				<td width="90%" rowspan="2">
					<p style="color:#0D256E; font-size:25px; font-weight:bold;">Rental Car Information</p>
			   		<table width="100%">
			   			<tr class="content-1">
			   				<td width="80%"><p style="color:#67758D; font-size:14px"><b>Agency Name:</b> <span>Hertz</span></p></td>
			   				<td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">14:30:00(MST)</p></td>
			   			</tr>
			   			<p style="width:100%; color:#fff; background-color:#fff; font-size:4px">e</p>
			   			<tr class="content-1">
			   				<td width="80%"><p style="color:#67758D; font-size:14px"><b>Location:</b> <span>Located at Airport</span></p></td>
			   				<td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">14:30:00(MST)</p></td>
			   			</tr>		   						   		
			   		</table>
				</td>
			</tr>
			<tr height:calc(100% - 0px)>
				<td style="text-align:center;height:calc(100% - 0px)">
					<img src="'.$dot_image1.'" width="2px" height="calc(100% - 24px)">
				</td>
			</tr>
		</table>
		<table class="body">
			<tr>
				<td width="10%">
					<div class="double" style="text-align:center;">
	   					<img src="'.$hotel_image.'" width="40px" height="40px">	
					</div>
				</td>
				<td width="90%" rowspan="2">
					<p style="color:#0D256E; font-size:25px; font-weight:bold;">Rental Car Information</p>
			   		<table width="100%">
			   			<tr class="content-1">
			   				<td width="80%"><p style="color:#67758D; font-size:14px"><b>Agency Name:</b> <span>Hertz</span></p></td>
			   				<td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">14:30:00(MST)</p></td>
			   			</tr>
			   			<p style="width:100%; color:#fff; background-color:#fff; font-size:4px">e</p>
			   			<tr class="content-1">
			   				<td width="80%"><p style="color:#67758D; font-size:14px"><b>Location:</b> <span>Located at Airport</span></p></td>
			   				<td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">14:30:00(MST)</p></td>
			   			</tr>		   						   		
			   		</table>
				</td>
			</tr>
			<tr height:calc(100% - 0px)>
				<td style="text-align:center;height:calc(100% - 0px)">
					<img src="'.$dot_image1.'" width="2px" height="calc(100% - 24px)">
				</td>
			</tr>
		</table>
		<table class="body">
			<tr>
				<td width="10%">
					<div class="double" style="text-align:center;">
	   					<img src="'.$flight_image.'" width="40px" height="40px">	
					</div>
				</td>
				<td width="90%" rowspan="2">
					<p style="color:#0D256E; font-size:25px; font-weight:bold;">Return Flight Information</p>
			   		<table width="100%">
			   			<tr class="title">
			   				<td width="50%"><p style="color:#3E4754; font-size:14px;">Flight:LH5678/Seat 11C</p></td>
			   				<td width="50%"><p style="color:#F39F32; font-size:14px; text-align:right">Date:2021-05-15</p></td>
			   			</tr>
			   			<p style="width:100%; color:#fff; background-color:#fff; font-size:4px">e</p>
			   			<p style="width:100%; background-color:#3E4754; font-size:0.5px">e</p>
			   			<div></div>
			   			<tr class="content-1">
			   				<td width="80%"><p style="color:#67758D; font-size:14px"><b>Departure:</b> <span>Denver International Airport, Denver, Colorado, United States of America</span></p></td>
			   				<td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">14:30:00(MST)</p></td>
			   			</tr>
			   			<p style="width:100%; color:#fff; background-color:#fff; font-size:4px">e</p>
			   			<tr class="content-1">
			   				<td width="80%"><p style="color:#67758D; font-size:14px"><b>Departure:</b> <span>Denver International Airport, Denver, Colorado, United States of America</span></p></td>
			   				<td width="20%"><p style="color:#F39F32; font-size:14px; text-align:right">14:30:00(MST)</p></td>
			   			</tr>		   						   		
						<div></div>
			   		</table>
				</td>
			</tr>
		</table>		
	</div>';			   			
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetXY(185, 285);
$pdf->SetTextColor(13,37,110);
$pdf->writeHTML("2 of 31", true, false, true, false, '');

//////////////////////////////////////////////////////page 3-------------------------///////////////////////////
$pdf->AddPage('P', 'A4');
$pdf->SetMargins(10, 12, 10);
$top_bar_image = K_PATH_IMAGES.'top_bar.png';
$warning = K_PATH_IMAGES.'warning.png';
$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$section_2 = new top_bar('Travel Advisories','The travel plan of tomorrow done right today'); 
$html = $section_2->html_content();
$html .= '<div class="section">
			<br><br><br><br><br>
			<table>
				<tr style="width:100%; text-align:center;">
					<td style="width:80%">
						<br>
						<p style="font-size:26px; text-align:left">Germany - Level 3: <span style="color:#eb9c34;">Reconsider Travel</span></p>
					</td>
					<td style="text-align:right; width:20%">
						<div style="text-align:right;">
   							<img src="'.$warning.'" width="80px" height="80px">
						</div>
					</td>
					<p style="width:90%; background-color:#3E4754; font-size:0.5px">e</p>
				</tr>
			</table>
			<div></div>
			<p style="text-align:left">Thu, 06 Aug 2020</p>
			<p  style="text-align:left">http://travel.state.gov/content/travel/en/traveladvisories/traveladvisories/germany-travel-advisory.html</p>
			<p style="text-align:justify; color:#67758D; font-size:15px; line-height: 25px;">Reconsider travel to Germany due to COVID-19. Exercise increased caution in Germany due to terrorism. Read the Department of State’s COVID-19 page before you plan any international travel. The Centers for Disease Control and Prevention (CDC) has issued a Level 4 Travel Health Notice for the Germany due to COVID-19. Improved conditions have been reported within Germany. Visit the Embassy\'s COVID-19 page for more information on COVID-19 in Germany. Terrorist groups continue plotting possible attacks in Germany. Terrorists may attack with little or no warning, targeting tourist locations, transportation hubs, markets/shopping malls, local government facilities, hotels, clubs, restaurants, places of worship, parks, major sporting and cultural events, educational institutions, airports, and other public areas. Read the country information page.
		If you decide to travel to Germany: See the U.S. Embassy\'s web page regarding COVID-19. Visit the CDC’s webpage on Travel and COVID-19. Be aware of your surroundings when traveling to tourist locations and crowded public venues. Follow the instructions of local authorities. Monitor local media for breaking events and adjust your plans based on new information. Enroll in the Smart Traveler Enrollment Program (STEP) to receive Alerts and make it easier to locate you in an emergency</p>
		</div>
	';
$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetXY(185, 285);
$pdf->SetTextColor(13,37,110);
$pdf->writeHTML("3 of 31", true, false, true, false, '');
/////////////////////////////////////////////page -4 /////////////////////////
$pdf->AddPage('P', 'A4');
$pdf->SetMargins(10, 12, 10);
$top_bar_image = K_PATH_IMAGES.'top_bar.png';
$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$section_2 = new top_bar('Fight Itinerary','The travel plan of tomorrow done right today'); 
$html = $section_2->html_content();
$pdf->Image($dark_back, 5, 50, 200, 200, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($left_card, 30, 90, 70, 120, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($right_card, 110, 90, 70, 120, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetFont($fontname, '', 11, '', false);
$pdf->SetTextColor(5,84,166);
$pdf->SetXY(35, 100);
$pdf->writeHTML("2021-05-15", true, false, true, false, '');
$pdf->SetXY(35, 120);
$pdf->writeHTML("14:30:00(MST)", true, false, true, false, '');
$pdf->SetXY(35, 200);
$pdf->writeHTML("14:30:00(MST)", true, false, true, false, '');
$pdf->SetXY(116, 200);
$pdf->writeHTML("14:30:00(MST)", true, false, true, false, '');
$pdf->SetTextColor(251,251,251);
$pdf->SetXY(116, 100);
$pdf->writeHTML("2021-05-15", true, false, true, false, '');
$pdf->SetXY(116, 120);
$pdf->writeHTML("14:30:00(MST)", true, false, true, false, '');
$pdf->SetTextColor(244,165,56);
$pdf->SetFont($fontname, '', 14, '', false);
$pdf->SetXY(116, 106);
$pdf->writeHTML("Germany International", true, false, true, false, '');
$pdf->SetXY(116, 112);
$pdf->writeHTML("Airport", true, false, true, false, '');
$pdf->SetTextColor(91,91,91);
$pdf->SetFont($fontname, '', 10, '', false);
$pdf->SetXY(35, 164);
$pdf->writeHTML("Flight", true, false, true, false, '');
$pdf->SetXY(70, 164);
$pdf->writeHTML("Seat", true, false, true, false, '');
$pdf->SetXY(116, 164);
$pdf->writeHTML("Flight", true, false, true, false, '');
$pdf->SetXY(151, 164);
$pdf->writeHTML("Seat", true, false, true, false, '');
$pdf->SetTextColor(13,37,110);
$pdf->SetFont($fontname, '', 19, '', false);
$pdf->SetXY(35, 170);
$pdf->writeHTML("LH5678", true, false, true, false, '');
$pdf->SetXY(70, 170);
$pdf->writeHTML("11C", true, false, true, false, '');
$pdf->SetXY(116, 170);
$pdf->writeHTML("LH5678", true, false, true, false, '');
$pdf->SetXY(151, 170);
$pdf->writeHTML("11C", true, false, true, false, '');
$pdf->SetFont($fontname, '', 14, '', false);
$pdf->SetXY(35, 106);
$pdf->writeHTML("Denver International", true, false, true, false, '');
$pdf->SetXY(35, 112);
$pdf->writeHTML("Airport", true, false, true, false, '');
$pdf->SetXY(35, 185);
$pdf->writeHTML("Germany International", true, false, true, false, '');
$pdf->SetXY(35, 191);
$pdf->writeHTML("Airport", true, false, true, false, '');
$pdf->SetXY(116, 185);
$pdf->writeHTML("Denver International", true, false, true, false, '');
$pdf->SetXY(116, 191);
$pdf->writeHTML("Airport", true, false, true, false, '');
$pdf->SetXY(185, 285);
$pdf->SetTextColor(13,37,110);
$pdf->writeHTML("4 of 31", true, false, true, false, '');
//////////////////////////////////page-5///////////////////////////////////

//////////////////////////////////page-6///////////////////////////////////

//////////////////////////////////page-7///////////////////////////////////

//////////////////////////////////page-8///////////////////////////////////
$pdf->AddPage('P', 'A4');
$pdf->SetMargins(10, 12, 10);
$top_bar_image = K_PATH_IMAGES.'top_bar.png';
$pdf->Image($top_bar_image, 0, 0, 0, 0, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left, 3, 275, 23, 22, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_left_text, 28, 287, 50, 2.5, '', '', '', false, 300, '', false, false, 0);
$pdf->Image($b_right, 174, 275, 37, 23, '', '', '', false, 300, '', false, false, 0);
$section_2 = new top_bar('Schedule Itinerary','The travel plan of tomorrow done right today'); 
$html = $section_2->html_content();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetXY(185, 285);
$pdf->SetTextColor(13,37,110);
$pdf->writeHTML("8 of 31", true, false, true, false, '');
//////////////////////////////////page-9///////////////////////////////////
$pdf->Output('Planiversity.pdf', 'I');