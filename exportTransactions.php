<?php
include_once("config.ini.php");
/** Include PHPExcel */
require_once dirname(__FILE__) . '/PHPExcel-1.8/Classes/PHPExcel.php';

if (!$auth->isLogged()) {
	header("Location:" . SITE . "login");
}
if ($userdata['account_type'] != 'Admin') {
	header("Location:" . SITE . "welcome");
}

$stmt = $dbh->prepare($_SESSION['exportQuery']);
$stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp = $stmt->execute();
$aux = '';
if ($tmp && $stmt->rowCount() > 0) {
	$user_ = $stmt->fetchAll(PDO::FETCH_OBJ);
	$bg1 = '';
	$c = 0;
	if ($_GET['mode'] == 'xls') {
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Planiversity")
			->setLastModifiedBy("Planiversity`")
			->setTitle("Admin Transactions Report")
			->setSubject("Admin Transactions Report");
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Email')
			->setCellValue('B1', 'Plan Type')
			->setCellValue('C1', 'Date Paid')
			->setCellValue('D1', 'Date Expire')
			->setCellValue('E1', 'Amount')
			->setCellValue('F1', 'Status');
		$rowCount = 2;
		foreach ($user_ as $user_row) {
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $user_row->email);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $user_row->plan_type);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, date('M d,Y h:i a', strtotime($user_row->date_paid)));
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, date('M d,Y h:i a', strtotime($user_row->date_expire)));
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $user_row->amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $user_row->status);
			$rowCount = $rowCount + 1;
		}
		$file = time() . "_Transactions_report.xls";
		header('Content-Type: application/vnd.openxmlformats-   officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $file . '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	} else if ($_GET['mode'] == 'csv') {
		$header .= "Email,Plan Type,Date Paid,Date Expire,Amount,Status\n";
		foreach ($user_ as $user_row) {
			$header .= $user_row->email . "," . $user_row->plan_type . "," . date('M d Y h:i a', strtotime($user_row->date_paid)) .
				"," . date('M d Y h:i a', strtotime($user_row->date_expire)) . "," . $user_row->amount . "," . $user_row->status . "\n";
		}
		if ($header == "") {
			$header = "\n(0) Records Found!\n";
		}
		$file = time() . "_Transactions_report.csv";
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=" . $file);
		header("Pragma: no-cache");
		header("Expires: 0");
		print $header;
	}
	echo $aux;
}
