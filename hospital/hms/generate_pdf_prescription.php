<?php
// generate_prescription.php
require_once('fpdf/fpdf.php');

function generatePrescriptionPDF($appointmentId, $con) {
    // Fetch all prescription data
    $query = mysqli_query($con, "
        SELECT 
            p.*, 
            d.doctorName, 
            d.specilization,
            a.appointmentDate,
            a.appointmentTime,
            a.appointment_number
        FROM tblpatient p
        JOIN appointment a ON a.appointment_number = p.appointment_number
        JOIN doctors d ON d.id = p.Docid
        WHERE a.id = '$appointmentId'
    ");

    if (!$query) {
        die("Query failed: " . mysqli_error($con));
    }

    $prescription = mysqli_fetch_assoc($query);
    if(!$prescription) {
        return false;
    }

    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Set document properties
    $pdf->SetTitle('Prescription - MEDIZEN');
    $pdf->SetAuthor('MEDIZEN');
    $pdf->SetCreator('MEDIZEN');

    // Color scheme
    $primaryColor = array(13, 110, 253); // Professional blue
    $secondaryColor = array(240, 248, 255); // Alice blue
    $accentColor = array(220, 230, 241); // Light blue-gray
    
    // Add logo (replace 'logo.png' with your actual logo file path)
    if (file_exists('assets/images/logo_no_bg.png')) {
        $pdf->Image('assets/images/logo_no_bg.png', 10, 10, 30);
    }
    
    // Header
    $pdf->SetY(15);
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(13, 110, 253); // Medical blue color
    $pdf->Cell(0,10,'MEDIZEN',0,1,'C');
    
    // contactus page info
    $contactQuery = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
    $contactData = mysqli_fetch_assoc($contactQuery);
        
    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0, 0, 0); // Black color
    $pdf->Cell(0,6,$contactData['PageDescription'],0,1,'C');
    $pdf->Cell(0,6,'Phone: ' .$contactData['MobileNumber']. ' | Email: '.$contactData['Email'],0,1,'C');
    
    // Divider line moved below header
    $pdf->SetDrawColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->SetLineWidth(0.8);
    $pdf->Line(10, 40, 200, 40);
    $pdf->Ln(15);

    /*// Doctor Information
    $pdf->SetFillColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $pdf->SetDrawColor($accentColor[0], $accentColor[1], $accentColor[2]);
    $pdf->SetLineWidth(0.3);
    $pdf->Rect(10, $pdf->GetY(), 190, 35, 'DF');
    
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(0,8,'  ATTENDING PHYSICIAN',0,1,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(50, 50, 50);
    
    $pdf->SetX(15);
    $pdf->Cell(45,6,'Doctor Name:',0,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,6,$prescription['doctorName'],0,0);
    
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,6,'Specialization:',0,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,6,$prescription['specilization'],0,1); */

    // Prescription title with ribbon effect
    $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->SetDrawColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,'  PRESCRIPTION  ',0,1,'C',true);
    
    // Add decorative elements
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->SetLineWidth(0.2);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(8);

    // Patient information section with modern card design
    $pdf->SetFillColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $pdf->SetDrawColor($accentColor[0], $accentColor[1], $accentColor[2]);
    $pdf->SetLineWidth(0.3);
    $pdf->Rect(10, $pdf->GetY(), 190, 35, 'DF');
    
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->Cell(0,8,'  PATIENT DETAILS',0,1,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(50, 50, 50);
    
    // Patient info in two columns
    $pdf->SetX(15);
    $pdf->Cell(45,6,'Patient Name:',0,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,6,$prescription['PatientName'],0,0);
    
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,6,'Appointment No:',0,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,6,$prescription['appointment_number'],0,1);
    
    $pdf->SetX(15);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(45,6,'Age:',0,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,6,$prescription['age'].' years',0,0);
    
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(30,6,'Date:',0,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,6,date('F j, Y', strtotime($prescription['appointmentDate'])).' at '.date('g:i A', strtotime($prescription['appointmentTime'])),0,1);
    
    $pdf->SetX(15);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(45,6,'Gender:',0,0);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,6,$prescription['PatientGender'],0,1);
    
    $pdf->Ln(12);

   // Medical History
    if(!empty($prescription['PatientMedhis'])) {
        $pdf->SetFillColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
        $pdf->SetDrawColor($accentColor[0], $accentColor[1], $accentColor[2]);
        $pdf->Rect(10, $pdf->GetY(), 190, 10, 'DF');
        
        $pdf->SetFont('Arial','B',11);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(0,8,'  MEDICAL HISTORY',0,1,'L');
        $pdf->Ln(3);
        
        $pdf->SetFont('Arial','',10);
        $pdf->SetTextColor(50, 50, 50);
        
        // Format prescription with numbered list and better spacing
        $medhis = explode("\n", $prescription['PatientMedhis']);
        $counter = 1;
        foreach($medhis as $med) {
            if(trim($med) != '') {
                $pdf->Cell(8,6,$counter.'.',0,0,'R');
                $pdf->MultiCell(0,6,trim($med),0,'L');
                $pdf->Ln(3);
                $counter++;
            }
        }
        $pdf->Ln(8);
    }
    // Prescription details with elegant formatting
    if(!empty($prescription['Prescription'])) {
        $pdf->SetFillColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
        $pdf->SetDrawColor($accentColor[0], $accentColor[1], $accentColor[2]);
        $pdf->Rect(10, $pdf->GetY(), 190, 10, 'DF');
        
        $pdf->SetFont('Arial','B',11);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(0,8,'  Rx',0,1,'L');
        $pdf->Ln(3);
        
        $pdf->SetFont('Arial','',10);
        $pdf->SetTextColor(50, 50, 50);
        
        // Format prescription with numbered list and better spacing
        $medicines = explode("\n", $prescription['Prescription']);
        $counter = 1;
        foreach($medicines as $medicine) {
            if(trim($medicine) != '') {
                $pdf->Cell(8,6,$counter.'.',0,0,'R');
                $pdf->MultiCell(0,6,trim($medicine),0,'L');
                $pdf->Ln(2);
                $counter++;
            }
        }
        $pdf->Ln(8);
    }

    // Recommended tests
    if(!empty($prescription['Tests'])) {
        $pdf->SetFillColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
        $pdf->SetDrawColor($accentColor[0], $accentColor[1], $accentColor[2]);
        $pdf->Rect(10, $pdf->GetY(), 190, 10, 'DF');
        
        $pdf->SetFont('Arial','B',11);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(0,8,'  TESTS',0,1,'L');
        $pdf->Ln(3);
        
        $pdf->SetFont('Arial','',10);
        $pdf->SetTextColor(50, 50, 50);
        
        // Format prescription with numbered list and better spacing
        $tests = explode("\n", $prescription['Tests']);
        $counter = 1;
        foreach($tests as $test) {
            if(trim($test) != '') {
                $pdf->Cell(8,6,$counter.'.',0,0,'R');
                $pdf->MultiCell(0,6,trim($test),0,'L');
                $pdf->Ln(3);
                $counter++;
            }
        }
        $pdf->Ln(8);
    }
    
    // Footer bar
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Rect(0, 260, 210, 30, 'F');

    // Left side: generated time
    $pdf->SetY(265);
    $pdf->SetFont('Arial','I',7);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0,5,'Generated on: '.date('F j, Y, g:i a'),0,0,'L');

    // Right side: Checked by + doctor + date
    $pdf->SetY(262);
    $pdf->SetFont('Arial','',9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetX(140); // Right position
    $pdf->Cell(60,5,'Checked by',0,1,'R');

    $pdf->SetFont('Arial','B',11);
    $pdf->SetX(140);
    $pdf->Cell(60,5,'Dr. '.$prescription['doctorName'].' ('.$prescription['specilization'].')',0,1,'R');

    /*$pdf->SetFont('Arial','I',8);
    $pdf->SetX(140);
    $pdf->Cell(60,5,date('F j, Y'),0,1,'R');*/

    /*$pdf->Cell(0,5,'Prescription ID: '.$prescription['appointment_number'],0,0,'R');*/

    // Save the PDF with improved naming
    $filename = 'RX_'.$prescription['appointment_number'].'_'.date('Y-m-d').'.pdf';
    if (!file_exists('prescriptions')) {
        mkdir('prescriptions', 0777, true);
    }
    $filepath = 'prescriptions/'.$filename;
    $pdf->Output('F', $filepath);

    return $filepath;
}