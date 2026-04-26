<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $data = $request->only(['pieImage', 'barImage', 'budgetImage', 'pieLabels', 'months', 'income', 'expense', 'budgetLabels', 'budgetAmounts', 'spentAmounts']);
        $filename = 'report_' . now()->format('Ymd_His') . '.pdf';

        // Render the PDF view with the provided images/data
        // Render view (report_pdf.blade.php)
        $html = view('report_pdf', $data)->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }
}
