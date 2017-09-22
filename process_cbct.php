<?php
//make checkboxes into lists
$fieldofview_list = implode('<br>', $_POST['fieldofview']);
$reasonforscan_list = implode('<br>', $_POST['reasonforscan']);
$scanoptions_list = implode('<br>', $_POST['scanoptions']);
$imageoutput_list = implode('<br>', $_POST['imageoutput']);


$upload_dir = "/www/mitools/assets/referrals/cbct/supporting_docs/";
$url_upload_dir = 'https://mitools.dent.umich.edu/assets/referrals/cbct/supporting_docs/';
$supporting_doc_html = '';
if (is_uploaded_file( $_FILES["referralfile"]["tmp_name"]) ) {
	move_uploaded_file($_FILES["referralfile"]["tmp_name"], $upload_dir.$_FILES["referralfile"]["name"]);
	$supporting_doc_html = '<a href="'.$url_upload_dir.$_FILES["referralfile"]["name"].'">Supporting File</a>';
}

$htmlout = <<<htmleoq

<h2>Cone Beam CT Referral Form</h2>

<h4>Referred By</h4>
<table class="table" cellspacing="0" cellpadding="5" border="1">
<tbody>
	<tr>
		<th>Doctor Name</th>
		<td>{$doctorname}</td>
	</tr>
	<tr>
		<th>Practice Name</th>
		<td>{$practicename}</td>
	</tr>
	<tr>
		<th>Street Address</th>
		<td>{$address} <br /> {$city}, {$state} {$zipcode}</td>
	</tr>
	<tr>
		<th>Phone Number</th>
		<td>{$phone}</td>
	</tr>
	<tr>
		<th>Fax Number</th>
		<td>{$fax}</td>
	</tr>
	<tr>
		<th>Email</th>
		<td>{$email}</td>
	</tr>
</tbody>
</table>

<table cellspacing="0" cellpadding="10" border="0">
<tr><td></td></tr>
</table>

<h4>Patient Information</h4>
<table class="table" cellspacing="0" cellpadding="5" border="1">
<tbody>
	<tr>
		<th>Patient Name</th>
		<td>{$patientname}</td>
	</tr>
	<tr>
		<th>Patient Phone</th>
		<td>{$patientphone}</td>
	</tr>
	<tr>
		<th>Date of Birth</th>
		<td>{$dob}</td>
	</tr>
	<tr>
		<th>Gender</th>
		<td>{$gender}</td>
	</tr>
	<tr>
		<th>Ethnicity</th>
		<td>{$ethnicity}</td>
	</tr>
	<tr>
		<th>Race</th>
		<td>{$race}</td>
	</tr>
	<tr>
		<th>Dental History and Medical Alerts</th>
		<td>{$historyalerts}</td>
	</tr>
</tbody>
</table>

<table cellspacing="0" cellpadding="10" border="0">
<tr><td></td></tr>
</table>

<h4>Options</h4>
<table class="table" cellspacing="0" cellpadding="5" border="1">
<tbody>
	<tr>
		<th>Field of View</th>
		<td>{$fieldofview_list} <br /> $fovcomment</td>
	</tr>
	<tr>
		<th>Reasons for Scan</th>
		<td>{$reasonforscan_list} <br /> $reasoncomment</td>
	</tr>
	<tr>
		<th>Scan Options</th>
		<td>{$scanoptions_list} <br /> $optcomment</td>
	</tr>
	<tr>
		<th>Field of View</th>
		<td>{$imageoutput_list} <br /> $datacomment</td>
	</tr>
	<tr>
		<th>Additional Comments</th>
		<td>{$referralcomment}</td>
	</tr>
</tbody>
</table>

<style>
.table {
    border-collapse: collapse !important;
}
.table {
  width: 100%;
  margin-bottom: 20px;
}
.table thead > tr > th,
.table tbody > tr > th,
.table tfoot > tr > th,
.table thead > tr > td,
.table tbody > tr > td,
.table tfoot > tr > td {
  padding: 8px;
  line-height: 1.428571429;
  vertical-align: top;
  border-top: 1px solid #dddddd;
  text-align: left !important;
}
</style>

htmleoq;

//echo $htmlout;


/************************************************ Create PDF ************************************************/

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
		$image_file = K_PATH_IMAGES.'signature-marketing_whitebg_40.png';
        $this->Image($image_file, PDF_MARGIN_LEFT, 10, 80, '', 'PNG', '', 'T', true, 300, '', false, false, 0, false, false, false);
		//Contact Info
	$contact_text = '<b>Erika Benavides, DDS, PhD</b> <br />
	Diplomate, American Board of Oral and Maxillofacial Radiology <br />
	University of Michigan School of Dentistry Dental Faculty Associates <br />
	1011 N. University Avenue, Room 1340, Ann Arbor, MI 48104-1078 <br />
	Tel: 734-764-3155 Fax: 734-615-4784';
        $this->SetFont('helvetica', '', 8);
		$this->SetTextColor(50,50,50); //gray
        $this->writeHTMLCell(0, 0, '', '', $contact_text, 0, 1, 0, true, 'R', true);
    }

    // Page footer
    public function Footer() {
		global $pdffilename;
        $this->SetFont('helvetica', '', 8);
		//$this->SetTextColor(255,203,5); //maize
		$this->SetTextColor(0,39,76); //blue
		$this->SetXY(-50, -20);
        //$this->Cell(100, 20, $search_text, 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->writeHTML("<em>$pdffilename</em>", true, false, true, false, '');
    }
}
/*  ----- This is fro dev, uncomment lines below for prod ------- */
$pdffilepath = '/usr/local/apache/referrals/htdocs/referrals/';
$pdffilename = 'cbct_'.date('YmdHis').'.pdf';
$email_path = 'https://referrals.dent.umich.edu/referrals/'.$pdffilename;
/*
$pdffilepath = '/www/mitools/assets/referrals/cbct/';
$pdffilename = 'cbct_'.date('YmdHis').'.pdf';
$email_path = 'https://referrals.dent.umich.edu/referrals/cbct/'.$pdffilename;
*/

$pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default header data
//$pdf->setPrintFooter(false);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($htmlout, true, false, true, false, '');
$pdf->Output($pdffilepath.$pdffilename, 'F');

$emailout = <<<htmleoq
	<p>A Cone Beam CT Referral Form has been filled out, it can be viewed at: <a href="{$email_path}">{$email_path}</a></p>
htmleoq;

$out_address = 'dent-cbct@umich.edu';
$mail = new my_phpmailer;
	$mail->ClearAddresses();
	//$mail->AddAddress($out_address);
	$mail->AddAddress('mbleed@umich.edu');
	$mail->Subject = "Cone Beam CT Referral Form";
	$mail->Body = $emaillout;
	$altbody = strip_tags($emailout);
	$mail->AltBody = $altbody;
	//$mail->Send();

//out message is printed to the screen in the main process script
$out_message = "Thanks for filling out the referral, it has been sent.";
?>
