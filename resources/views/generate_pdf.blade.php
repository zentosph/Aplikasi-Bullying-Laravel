<?php
// Include the Composer autoload file
require_once(base_path('Vendor/autoload.php'));

class CustomPDF extends TCPDF {
    // Page footer
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Create a new instance of the custom TCPDF class
$pdf = new CustomPDF();

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Laporan User');
$pdf->SetSubject('Laporan User');

// Set margins
$pdf->SetMargins(10, 20, 10); // Adjust margins as needed

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Get data from view variables
$startDate = isset($startDate) ? $startDate : '0000-00-00';
$endDate = isset($endDate) ? $endDate : '9999-12-31';

// Add report title
$pdf->SetXY(10, 20); // Set X and Y position
$pdf->SetFont('helvetica', 'B', 16); // Larger font for report title
$pdf->Cell(0, 10, 'Laporan Kasus', 0, 1, 'L'); // Report title, aligned to the left

// Add date range
$pdf->SetXY(10, 35); // Adjust Y position for the date range
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Tanggal Kasus: ' . $startDate . ' - ' . $endDate, 0, 1, 'L'); // Date range, aligned to the left

// Prepare HTML content
$html = '<table border="1" cellspacing="0" cellpadding="4">';
$html .= '<thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Status</th>
            </tr>
          </thead>';
$html .= '<tbody>';

// Add items
$no = 1;
foreach ($kelas as $key) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($no++, ENT_QUOTES, 'UTF-8') . '</td>';
    $html .= '<td>' . htmlspecialchars($key->username, ENT_QUOTES, 'UTF-8') . '</td>';
    $html .= '<td>' . htmlspecialchars($key->nama, ENT_QUOTES, 'UTF-8') . '</td>';
    $html .= '<td>' . htmlspecialchars($key->kelas, ENT_QUOTES, 'UTF-8') . '</td>';
    $html .= '<td>' . htmlspecialchars($key->status, ENT_QUOTES, 'UTF-8') . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';

// Output the HTML table
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF directly to the browser
$pdfContent = $pdf->Output('laporan_user.pdf', 'S'); // 'S' returns the PDF as a string

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="laporan_user.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($pdfContent));
ob_clean();
flush();
echo $pdfContent;

// Exit the script
exit;

?>
