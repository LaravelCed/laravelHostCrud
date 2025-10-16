<?php

namespace App\Http\Controllers;

use App\Models\TblRecord;
use App\Models\TblSignature;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

class indexController extends Controller
{
    public function addTask(Request $request) {
        $task = $request->input('task');
        $filename = $request->file('filename');
        $exeFileUpload = 0;
        $exeAddTask = 0;

        $extension = strtolower($filename->getClientOriginalExtension());

        if (in_array($extension, ['doc', 'docx'])) {
            $exeFileUpload = 1;

            // Convert DOC/DOCX to PDF
            $pdfPath = $this->convertDocToPdf($filename, $extension);

            if ($pdfPath) {
                $result = TblRecord::create([
                    'task'=>$task,
                    'filename'=>pathinfo($filename->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf',
                    'path'=>$pdfPath
                ]);

                if ($result) {
                    $exeAddTask = 1;
                }
            }
        }

        $readTblRecord = TblRecord::all();
        $readTblSignature = TblSignature::all();
        return view('index', compact('exeAddTask','exeFileUpload', 'readTblRecord', 'readTblSignature'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    private function convertDocToPdf($file, $extension) {
        try {
            // Store uploaded Word file temporarily
            $tempPath = $file->store('temp', 'public');
            $fullTempPath = storage_path('app/public/' . $tempPath);

            // Load Word file
            $phpWord = IOFactory::load($fullTempPath);

            // Detect Word document's paper size
            $sectionStyle = $phpWord->getSections()[0]->getStyle();
            $pageWidth = $sectionStyle->getPageSizeW();
            $pageHeight = $sectionStyle->getPageSizeH();

            // Convert twips to millimeters
            $widthMm = $pageWidth * 25.4 / 1440;
            $heightMm = $pageHeight * 25.4 / 1440;

            // Match to closest DomPDF paper size
            $paperType = 'A4';
            $sizes = [
                'A4' => ['w'=>210, 'h'=>297],
                'Letter' => ['w'=>216, 'h'=>279],
                'Legal' => ['w'=>216, 'h'=>356],
                'A3' => ['w'=>297, 'h'=>420],
                'A5' => ['w'=>148, 'h'=>210],
            ];
            foreach ($sizes as $name => $size) {
                $diff = abs($widthMm - $size['w']) + abs($heightMm - $size['h']);
                if (!isset($bestDiff) || $diff < $bestDiff) {
                    $bestDiff = $diff;
                    $paperType = $name;
                }
            }

            // Detect orientation
            $orientation = $widthMm > $heightMm ? 'landscape' : 'portrait';

            // Convert Word to HTML for DomPDF
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
            $htmlPath = storage_path('app/public/temp_' . uniqid() . '.html');
            $htmlWriter->save($htmlPath);

            // Inject default font + size (always overwrite inline styles)
            $htmlContent = file_get_contents($htmlPath);

            // Remove any old style blocks or font-family/font-size inline styles to force apply our own
            $htmlContent = preg_replace('/font-family:[^;"]*;?/i', '', $htmlContent);
            $htmlContent = preg_replace('/font-size:[^;"]*;?/i', '', $htmlContent);

            $styleBlock = "<style>
                @page { margin: 1in; }
                body { font-family: 'DejaVu Sans', sans-serif !important; font-size: 10pt !important; }
                p, span, div, td, th, li { font-family: 'DejaVu Sans', sans-serif !important; font-size: 10pt !important; }
            </style>";
            $htmlContent = $styleBlock . $htmlContent;

            // Load HTML into DomPDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper($paperType, $orientation);
            $dompdf->render();

            // Save generated PDF
            $pdfFileName = 'files/' . uniqid() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';
            $pdfFullPath = storage_path('app/public/' . $pdfFileName);

            $directory = dirname($pdfFullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($pdfFullPath, $dompdf->output());

            // Clean up temporary files
            Storage::disk('public')->delete($tempPath);
            @unlink($htmlPath);

            return $pdfFileName;

        } catch (\Exception $e) {
            return null;
        }
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function deleteTask($id) {
        $exeDeleteTask = 0;

        $result = TblRecord::where('id',$id)->delete();

        if ($result) {
            $exeDeleteTask = 1;
        }

        $readTblRecord = TblRecord::all();
        $readTblSignature = TblSignature::all();
        return view('index', compact('exeDeleteTask', 'readTblRecord', 'readTblSignature'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function addSignature(Request $request) {
        $signature_name = $request->input('signature_name');
        $signature_file = $request->file('signature_file');
        $exeSignatureUpload = 0;
        $exeAddSignature = 0;

        if ($signature_file->getClientOriginalExtension() === 'png') {
            $exeSignatureUpload = 1;

            $signature_path = $signature_file->store('files','public');

            $result = TblSignature::create([
                'signature_name'=>$signature_name,
                'signature_file'=>$signature_file->getClientOriginalName(),
                'signature_path'=>$signature_path
            ]);

            if ($result) {
                $exeAddSignature = 1;
            }
        }

        $readTblRecord = TblRecord::all();
        $readTblSignature = TblSignature::all();
        return view('index', compact('exeSignatureUpload', 'exeAddSignature', 'readTblRecord', 'readTblSignature'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function editTask(Request $request, $id) {
        $exeFileUpload = 0;
        $exeEditTask = 0;
        $task = $request->input('task');
        $filename = $request->file('filename');

        $extension = strtolower($filename->getClientOriginalExtension());

        if (in_array($extension, ['doc', 'docx'])) {
            $exeFileUpload = 1;

            $pdfPath = $this->convertDocToPdf($filename, $extension);

            if ($pdfPath) {
                $result = TblRecord::where('id',$id)->update([
                    'task'=>$task,
                    'filename'=>pathinfo($filename->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf',
                    'path'=>$pdfPath
                ]);

                if ($result) {
                    $exeEditTask = 1;
                }
            }
        }

        $readTblRecord = TblRecord::all();
        $readTblSignature = TblSignature::all();
        $checkTblRecord = TblRecord::where('id',$id)->first();
        return view('edit', compact('exeFileUpload','exeEditTask', 'readTblRecord', 'readTblSignature', 'id', 'checkTblRecord'));
    }

    public function addSignatureToPDF(Request $request)
    {
        $signature_id = $request->input('signature_id');
        $pdf_path_url = $request->input('pdf_path');
        $posXPercent = floatval($request->input('pos_x_percent'));
        $posYPercent = floatval($request->input('pos_y_percent'));
        $pageNumber = intval($request->input('page_number')) ?? 1;
        $canvasWidth = floatval($request->input('canvas_width'));
        $canvasHeight = floatval($request->input('canvas_height'));

        $exeSignatureOnPdf = 0;

        try {
            // Convert public URL to relative path
            $relativePdfPath = str_replace(asset('storage') . '/', '', $pdf_path_url);
            $pdfFullPath = storage_path('app/public/' . $relativePdfPath);

            // Get signature data
            $signature = TblSignature::where('signature_id', $signature_id)->first();
            if (!$signature || !file_exists(storage_path('app/public/' . $signature->signature_path))) {
                throw new \Exception('Signature not found.');
            }
            $signaturePath = storage_path('app/public/' . $signature->signature_path);

            // Get signature image dimensions
            list($sigWidth, $sigHeight) = getimagesize($signaturePath);

            // Create FPDI instance
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($pdfFullPath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplId = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tplId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);

                // If this is the page chosen, add the signature
                if ($i == $pageNumber) {
                    // Canvas rendering is at scale 1.5, so actual PDF dimensions differ
                    // Calculate the scale factor between canvas and actual PDF
                    $scale = 1.5;
                    
                    // The canvas width/height already includes the scale
                    // So we need to find the actual PDF dimensions by dividing by scale
                    $actualPdfWidth = $canvasWidth / $scale;
                    $actualPdfHeight = $canvasHeight / $scale;
                    
                    // Now calculate position on actual PDF using percentages
                    $pdfX = ($posXPercent / 100) * $size['width'];
                    $pdfY = ($posYPercent / 100) * $size['height'];

                    // Calculate signature size in PDF (40mm width)
                    $sigWidthMM = 40;
                    $sigHeightMM = ($sigHeight / $sigWidth) * $sigWidthMM;

                    // Place signature
                    $pdf->Image($signaturePath, $pdfX, $pdfY, $sigWidthMM, $sigHeightMM, 'PNG');
                }
            }

            // Overwrite the same file
            $pdf->Output($pdfFullPath, 'F');

            $exeSignatureOnPdf = 1;
        } catch (\Exception $e) {
            $exeSignatureOnPdf = 0;
        }

        $readTblRecord = TblRecord::all();
        $readTblSignature = TblSignature::all();
        return view('index', compact('exeSignatureOnPdf', 'readTblRecord', 'readTblSignature'));
    }


}