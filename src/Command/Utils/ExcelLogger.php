<?php

namespace App\Command\Utils;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpKernel\KernelInterface;

class ExcelLogger
{
    private $logDirectory;
    private $excelFilepath;
    /**
     * @var Xlsx
     */
    private $writer;
    /**
     * @var Spreadsheet
     */
    private $spreadsheet;
    /**
     * @var Worksheet
     */
    private $sheet;

    public function __construct(KernelInterface $kernel)
    {
        $this->logDirectory = $kernel->getProjectDir() . '/var/log';
    }

    public function initFile(string $fileName)
    {
        $this->excelFilepath = $this->logDirectory . "/" . $fileName . ".xlsx";
        $this->spreadsheet = new Spreadsheet();
        $this->writer = new Xlsx($this->spreadsheet);
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function write(array $values, int $row, $cellColor = Color::COLOR_WHITE)
    {
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
                'color' => Color::COLOR_BLACK,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'argb' => $cellColor,
                ],
            ],
        ];
        $column = 0;
        $cellCoordinates = '';
        foreach ($values as $value) {
            $cellCoordinates = chr(65 + $column) . $row;
            $this->sheet->setCellValue($cellCoordinates, $value);
            $column++;
        }
        $cellCoordinates = 'A' . $row . ':' . $cellCoordinates;
        $this->sheet->getStyle($cellCoordinates)->applyFromArray($styleArray);
    }

    public function save()
    {
        $this->writer->save($this->excelFilepath);
    }
}