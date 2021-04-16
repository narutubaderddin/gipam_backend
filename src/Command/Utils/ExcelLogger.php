<?php


namespace App\Command\Utils;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
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

    public function write(array $values, int $row)
    {
        $column = 0;
        foreach ($values as $value) {
            $cell = chr(65 + $column) . $row;
            $this->sheet->setCellValue($cell, $value);
            $column++;
        }
    }

    public function save()
    {
        $this->writer->save($this->excelFilepath);
    }
}