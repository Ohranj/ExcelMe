<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ReadSheetInfo
{
    public function listFirstSheetInfo($file, $extension): array
    {
        $reader = IOFactory::createReader(ucfirst($extension));
        return $reader->listWorksheetInfo($file)[0];
    }
}
