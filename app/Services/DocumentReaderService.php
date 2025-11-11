<?php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;

class DocumentReaderService
{
    /**
     * Đọc nội dung file tài liệu (txt, pdf, doc, docx)
     *
     * @param \Illuminate\Http\UploadedFile|string $file
     * @return string
     */
    public function read($file): string
    {
        $path = is_string($file) ? $file : $file->getRealPath();
        $extension = is_string($file)
            ? strtolower(pathinfo($file, PATHINFO_EXTENSION))
            : strtolower($file->getClientOriginalExtension());


        switch ($extension) {
            case 'txt':
                return $this->readTxt($path);

            case 'pdf':
                return $this->readPdf($path);

            case 'doc':
            case 'docx':
                return $this->readDoc($path);

            default:
                throw new \Exception("Định dạng {$extension} chưa được hỗ trợ.");
        }
    }

    private function readTxt(string $path): string
    {
        return file_get_contents($path);
    }

    private function readPdf(string $path): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($path);
        return $pdf->getText();
    }

    private function readDoc(string $path): string
    {
        $phpWord = IOFactory::load($path);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }

        return $text;
    }
}
