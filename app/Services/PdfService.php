<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

class PdfService
{
    public function embedSignature($filePath, $signature)
    {
        $pdf = new \TCPDF();
        $pdf->setSourceFile($filePath);
        $tplIdx = $pdf->importPage(1);

        $pdf->AddPage();
        $pdf->useTemplate($tplIdx, 10, 10, 100);

        $pdf->Text(10, 160, 'Signature: ' . $signature);

        $pdf->Output($filePath, 'F');
    }

    public function verifySignature($filePath)
    {
        //not allowed to use this, so no more development on it.
    }
}