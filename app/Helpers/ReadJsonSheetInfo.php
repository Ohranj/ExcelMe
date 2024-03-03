<?php

namespace App\Helpers;

use App\Interfaces\FileMetaInterface;

class ReadJsonSheetInfo implements FileMetaInterface
{
    protected $file = null;
    protected $extension = null;
    protected $json = null;
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
    public function __construct($file, string $extension)
    {
        $this->file = $file;
        $this->extension = $extension;
        $this->json = $this->instantiateJsonContent();
    }

    /**
     * 
     */
    public function instantiateJsonContent(): array
    {
        $fileContent = file_get_contents($this->file);
        return json_decode($fileContent, true);
    }


    /**
     * 
     */
    public function headings()
    {
        $this->meta['headings'] = array_keys($this->json[0]);
        return $this;
    }

    /**
     * 
     */
    public function totalRows()
    {
        $this->meta['totals']['rows'] = count($this->json);
        return $this;
    }

    /**
     * 
     */
    public function totalCols()
    {
        $this->meta['totals']['cols'] = count($this->json[0]);
        return $this;
    }
}
