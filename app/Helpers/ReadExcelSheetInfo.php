<?php

namespace App\Helpers;

use App\Interfaces\FileMetaInterface;
use Maatwebsite\Excel\HeadingRowImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;

class ReadExcelSheetInfo implements FileMetaInterface
{
    protected $file = null;
    protected $extension = null;
    protected $sheetReader = null;
    public $meta = [
        'headings' => [],
        'totals' => [
            'rows' => 0,
            'cols' => 0
        ]
    ];

    /**
     * 
     */
    public function __construct($file, string $extension, int $sheet)
    {
        $this->file = $file;
        $this->extension = $extension;
        $this->sheetReader = $this->instantiateReader()->listWorksheetInfo($this->file)[$sheet];
    }

    /**
     * 
     */
    public function instantiateReader(): IReader
    {
        return IOFactory::createReader(ucfirst($this->extension));
    }

    /**
     * 
     */
    public function columns()
    {
        $this->meta['headings'] = (new HeadingRowImport())->toArray($this->file);
        return $this;
    }

    /**
     * 
     */
    public function totalRows()
    {
        $this->meta['totals']['rows'] = $this->sheetReader['totalRows'];
        return $this;
    }

    /**
     * 
     */
    public function totalCols()
    {
        $this->meta['totals']['cols'] = $this->sheetReader['totalColumns'];
        return $this;
    }
}
