<?php
require('fpdf/fpdf.php');

function generateTestPDF($order_id, $con) {
    // Get order details
    $order_query = mysqli_query($con, "SELECT o.*, u.fullName, u.email 
                                      FROM test_orders o
                                      JOIN users u ON o.user_id = u.id
                                      WHERE o.id = '$order_id'");
    $order = mysqli_fetch_assoc($order_query);

    // Get ordered tests
    $tests_query = mysqli_query($con, "SELECT t.name, t.price, c.name as category_name
                                      FROM ordered_tests ot
                                      JOIN diagnostic_tests t ON ot.test_id = t.id
                                      JOIN test_categories c ON t.category_id = c.id
                                      WHERE ot.order_id = '$order_id'");

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set document properties
    $pdf->SetTitle('Appointment Slip - MEDIZEN');
    $pdf->SetAuthor('MEDIZEN');
    $pdf->SetCreator('MEDIZEN');

    // Add logo
    if (file_exists('assets/images/logo_no_bg.png')) {
        $pdf->Image('assets/images/logo_no_bg.png', 10, 10, 30);
    }

    // Set header
    $pdf->SetY(15);
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(13, 110, 253);
    $pdf->Cell(0,10,'MEDIZEN',0,1,'C');

    // contactus page info
    $contactQuery = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
    $contactData = mysqli_fetch_assoc($contactQuery);

    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0,6,$contactData['PageDescription'],0,1,'C');
    $pdf->Cell(0,6,'Phone: '.$contactData['MobileNumber'].' | Email: '.$contactData['Email'],0,1,'C');

    // Divider
    $pdf->SetDrawColor(13, 110, 253);
    $pdf->SetLineWidth(0.6);
    $pdf->Line(10, 40, 200, 40);
    $pdf->Ln(15);

    // Appointment slip title
    $pdf->SetFont('Arial','B',16);
    $pdf->SetTextColor(13, 110, 253);
    $pdf->Cell(0,10,'APPOINTMENT SLIP',0,1,'C');
    $pdf->Ln(8);

    // Patient Info
    $pdf->SetFont('Arial','B',13);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0,10,'Patient Information',0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(51, 51, 51);
    $pdf->Cell(50,7,'Name:',0,0);
    $pdf->Cell(0,7,$order['fullName'],0,1);
    $pdf->Cell(50,7,'Order Number:',0,0);
    $pdf->Cell(0,7,$order['order_number'],0,1);
    $pdf->Cell(50,7,'Appointment Date:',0,0);
    $pdf->Cell(0,7, (!empty($order['test_date']) ? date('F j, Y', strtotime($order['test_date'])) : 'N/A'),0,1);
    $pdf->Cell(50,7,'Appointment Time:',0,0);
    $pdf->Cell(0,7, (!empty($order['test_time']) ? date('h:i A', strtotime($order['test_time'])) : 'N/A'),0,1);
    $pdf->Ln(5);

    // Tests Booked
    $pdf->SetFont('Arial','B',13);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0,10,'Tests Booked',0,1);

    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(221, 235, 255);
    $pdf->SetTextColor(0,0,0);

    $pdf->Cell(80,8,'Test Name',1,0,'C', true);
    $pdf->Cell(50,8,'Category',1,0,'C', true);
    $pdf->Cell(30,8,'Price',1,1,'C', true);

    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(51, 51, 51);
    $total = 0;
    while($test = mysqli_fetch_assoc($tests_query)) {
        $pdf->Cell(80,7,$test['name'],1,0);
        $pdf->Cell(50,7,$test['category_name'],1,0);
        $pdf->Cell(50,7,number_format($test['price'],2).' BDT',1,1,'R');
        $total += $test['price'];
    }

    // Total
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(130,7,'Total:',1,0,'R');
    $pdf->Cell(30,7,number_format($total,2).' BDT',1,1,'R');
    $pdf->Ln(10);

    // Instructions
    $pdf->SetFont('Arial','I',10);
    $pdf->SetTextColor(51, 51, 51);
    $pdf->MultiCell(0,5,'Please bring this slip when you come for your tests. Fasting may be required for some tests as per preparation instructions.',0,1);

    // Save PDF
    $filename = 'test_slip_'.$order_id.'.pdf';
    $filepath = 'test_slips/'.$filename;

    if(!file_exists('test_slips')) {
        mkdir('test_slips', 0777, true);
    }

    $pdf->Output('F', $filepath);
    return $filepath;
}
?>
