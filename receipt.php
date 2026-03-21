<?php
    /*****************************************************************************************************************************************
     * Title: FPDF
     * Author: FPDF
     * Site: fpdf.org
     * Date: 21/03/26
     * Code Version: 1.86
     * Availability: https://www.fpdf.org
     * Accessed: 21/03/26
     * Modified: No modifications made the code. Using FPDF library in the project to create a receipt when a loan transaction is processed.
     ****************************************************************************************************************************************/

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once("lib/fpdf/fpdf.php");
    require_once("config/config.php");

    // DATA FOR RECEIPT
    $libraryName = "Killorglin Public Library";
    $libraryAddress = "Library Place, Killorglin, Co. Kerry, V93 E221";
    $libraryWebsite = "kerrylibrary.ie";

    $member = $_SESSION["Member"];
    $memberName = $member->getFirstName() . " " . $member->getLastName();
    $memberID = $member->getId();

    // https://www.w3schools.com/php/php_date.asp
    // https://www.php.net/manual/en/timezones.europe.php
    date_default_timezone_set("Europe/Dublin");
    $date = date("F j, Y");
    $time = date("h:i a");

    // https://www.php.net/manual/en/datetime.createfromformat.php
    $dueDate = DateTime::createFromFormat('Y-m-d', $_SESSION["DueDate"])->format("F j, Y");

    $transactionInfo = [
        "Member: " => $memberName,
        "ID: " => $memberID,
        "Date: " => $date,
        "Time: " => $time
    ];

    $books = $_SESSION["BooksInCart"];
    $itemCount = count($books);

    $navyR = 29; $navyG = 53; $navyB = 87;              
    $lightNavyR = 45; $lightNavyG = 75; $lightNavyB = 110;  
    $rowGreyR = 240; $rowGreyG = 244; $rowGreyB = 248;      
    $borderR = 180; $borderG = 190; $borderB = 200;         
    $mutedR = 107; $mutedG = 114; $mutedB = 128;            
    $dividerR = 100; $dividerG = 120; $dividerB = 140;

    // PDF CREATION
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(20, 20, 20);

    // LIBRARY NAME
    $pdf->SetFont("Arial", "B", 20);
    $pdf->SetTextColor($navyR, $navyG, $navyB);
    $pdf->Cell(0, 10, $libraryName, 0, 1, "C");

    // LIBRARY ADDRESS
    $pdf->SetFont("Arial", "", 15);
    $pdf->SetTextColor($lightNavyR, $lightNavyG, $lightNavyB);
    $pdf->Cell(0, 5, $libraryAddress, 0, 1, "C");
    $pdf->Cell(0, 5, $libraryWebsite, 0, 1, "C");

    // LINE DIVIDOR
    $pdf->Ln(5);
    $pdf->SetDrawColor($navyR, $navyG, $navyB);
    $pdf->SetLineWidth(1);
    $pdf->Line(20, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);

    // CHECKOUT SECTION
    $pdf->SetFont("Arial", "B", 15);
    $pdf->SetTextColor($navyR, $navyG, $navyB);
    $pdf->Cell(0, 5, "Checkout Receipt", 0, 1, "C");
    $pdf->Ln(5);

    // TRANSACTION INFORMATION
    foreach($transactionInfo as $info => $value) {
        $pdf->SetFont("Arial", "B", 12);
        $pdf->SetTextColor($navyR, $navyG, $navyB);
        $pdf->Cell(40, 10, $info, 0, 0);

        $pdf->SetFont("Arial", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 10, $value, 0, 1);
    }

    // LINE DIVIDOR
    $pdf->Ln(5);
    $pdf->SetDrawColor($navyR, $navyG, $navyB);
    $pdf->SetLineWidth(1);
    $pdf->Line(20, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);

    // BOOKS TABLE
    $pdf->SetFillColor($navyR, $navyG, $navyB);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->SetDrawColor($navyR, $navyG, $navyB);
    $pdf->SetLineWidth(0.3);

    // BOOKS TABLE HEADINGS
    $pdf->Cell(15, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(125, 10, 'Title / Author', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Due Date', 1, 1, 'C', true);

    // BOOKS TABLE DATA
    $rowNumber = 1;

    foreach($books as $book) {
        $fill = ($rowNumber % 2 == 0);
        $pdf->SetFillColor($rowGreyR, $rowGreyG, $rowGreyB);
        $pdf->SetDrawColor($borderR, $borderG, $borderB);
        $pdf->SetLineWidth(0.2);

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        // TITLE/AUTHOR COLUMN
        $titleX = $x + 15;
        $pdf->SetXY($titleX, $y);
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell(125, 10, $book->getTitle() . "\n" . $book->getAuthor(), 1, 'L', $fill);
        $updatedY = $pdf->GetY();
        $rowHeight = $updatedY - $y;

        // BOOK ID COLUMN
        $pdf->SetXY($x, $y);
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(15, $rowHeight, $rowNumber, 1, 0, 'C', $fill);

        // DUE DATE COLUMN
        $pdf->SetXY($titleX + 125, $y);
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(30, $rowHeight, $dueDate, 1, 0, 'C', $fill);

        $pdf->SetY($updatedY);
        $rowNumber++;
    }

    // LINE DIVIDOR
    $pdf->Ln(5);
    $pdf->SetDrawColor($navyR, $navyG, $navyB);
    $pdf->SetLineWidth(1);
    $pdf->Line(20, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);

    // CHECKED OUT ITEMS
    $pdf->SetFont("Arial", "B", 10);
    $pdf->SetTextColor($navyR, $navyG, $navyB);
    $pdf->Cell(150, 8, 'Items checked out:', 0, 0);
    $pdf->Cell(20, 8, $itemCount, 0, 1, 'R');

    // LINE DIVIDOR
    $pdf->Ln(3);
    $pdf->SetDrawColor($dividerR, $dividerG, $dividerB);
    $pdf->SetLineWidth(0.4);
    $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
    $pdf->Ln(6);

    // FOOTER
    $pdf->SetFont('Arial', 'I', 9);
    $pdf->SetTextColor($mutedR, $mutedG, $mutedB);
    $pdf->Cell(0, 5, 'Thank you for visiting Killorglin Public Library!', 0, 1, 'C');
    $pdf->Cell(0, 5, 'General lending period is 5 days.', 0, 1, 'C');

    // EURO SYMBOL WORKAROUND AS FPDF USES ISO-8859 ENCODING - USING DOLLAR
    $pdf->Cell(0, 5, "Late fees: " . $euro . "$0.20/day per item", 0, 1, 'C');

    ob_end_clean();
    $pdf->Output('I', 'LoanReceipt.pdf');
?>