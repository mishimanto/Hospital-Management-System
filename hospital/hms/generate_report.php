<?php
// generate-report.php
require_once('fpdf/fpdf.php');

function generateDiagnosticReportPDF($orderId, $con) {
    $query = mysqli_query($con, "
        SELECT 
            t.*, 
            u.fullName, u.address, u.city, u.gender, u.email
        FROM test_orders t
        JOIN users u ON u.id = t.user_id
        WHERE t.id = '$orderId'
    ");

    if (!$query) {
        die("Query failed: " . mysqli_error($con));
    }

    $order = mysqli_fetch_assoc($query);
    if(!$order) {
        return false;
    }

    $testsQuery = mysqli_query($con, "
        SELECT ot.*, dt.name as testName, dt.description, dt.normal_range
        FROM ordered_tests ot
        JOIN diagnostic_tests dt ON dt.id = ot.test_id
        WHERE ot.order_id = '$orderId'
    ");

    if (!$testsQuery) {
        die("Test query failed: " . mysqli_error($con));
    }

    $pdf = new FPDF();
    $pdf->AddPage();

    // Set document properties
    $pdf->SetTitle('Diagnostic Report - MEDIZEN');
    $pdf->SetAuthor('MEDIZEN');
    $pdf->SetCreator('MEDIZEN');

    // Add logo
    if (file_exists('assets/images/logo_no_bg.png')) {
        $pdf->Image('assets/images/logo_no_bg.png', 10, 10, 30);
    }

    // Header
    $pdf->SetY(15);
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(13, 110, 253);
    $pdf->Cell(0,10,'MEDIZEN',0,1,'C');

    // Contact info
    $contactQuery = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
    $contactData = mysqli_fetch_assoc($contactQuery);

    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0,6,$contactData['PageDescription'],0,1,'C');
    $pdf->Cell(0,6,'Phone: '.$contactData['MobileNumber'].' | Email: '.$contactData['Email'],0,1,'C');

    $pdf->SetDrawColor(13, 110, 253);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, 40, 200, 40);
    $pdf->Ln(15);

    // Report Title
    $pdf->SetFont('Arial','B',16);
    $pdf->SetTextColor(13, 110, 253);
    $pdf->Cell(0,10,'DIAGNOSTIC REPORT',0,1,'C');
    $pdf->Ln(8);

    // Patient Details Box
    $pdf->SetFillColor(233, 242, 255);
    $pdf->Rect(10, $pdf->GetY(), 190, 10, 'F');
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0,10,'PATIENT DETAILS',0,1,'L');
    $pdf->Ln(5);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Patient Name:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$order['fullName'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Gender:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$order['gender'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Order Number:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$order['order_number'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Test Date:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$order['test_date'].' '.$order['test_time'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Email:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$order['email'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Address:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->MultiCell(0,8,$order['address'].', '.$order['city'],0,1);
    $pdf->Ln(12);

    // Test Results Box
    $pdf->SetFillColor(233, 242, 255);
    $pdf->Rect(10, $pdf->GetY(), 190, 10, 'F');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'TEST RESULTS',0,1,'L');
    $pdf->Ln(5);

    while ($test = mysqli_fetch_assoc($testsQuery)) {
        $pdf->SetFont('Arial','B',11);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,8,$test['testName'],0,1);

        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,6,$test['description'],0,'L');

        if ($test['status'] == 'Completed' && $test['result']) {
            $pdf->Ln(2);
            $pdf->Cell(30,6,'Result:',0,0);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0,6,$test['result'],0,1);

            $pdf->SetFont('Arial','',10);
            $pdf->Cell(30,6,'Normal Range:',0,0);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0,6,$test['normal_range'],0,1);

            $pdf->Cell(30,6,'Status:',0,0);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0,6,'Completed',0,1);
        } else {
            $pdf->Cell(30,6,'Status:',0,0);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(0,6,ucfirst($test['status']),0,1);
        }

        $pdf->Ln(8);
    }

    // Footer
    $pdf->SetY(270);
    $pdf->SetFont('Arial','I',8);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0,5,'Generated on: '.date('F j, Y, g:i a'),0,0,'L');
    $pdf->Cell(0,5,''.$pdf->PageNo());
    $pdf->Ln(5);

    $filename = 'Diagnostic_Report_'.$order['order_number'].'_'.date('Ymd_His').'.pdf';
    if (!file_exists('diagnostic_reports')) {
        mkdir('diagnostic_reports', 0777, true);
    }
    $filepath = 'diagnostic_reports/'.$filename;
    $pdf->Output('F', $filepath);

    return $filepath;
}
