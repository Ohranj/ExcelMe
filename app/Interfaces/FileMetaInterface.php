<?php

namespace App\Interfaces;

interface FileMetaInterface
{
    public function headings();
    public function totalRows();
    public function totalCols();
}
