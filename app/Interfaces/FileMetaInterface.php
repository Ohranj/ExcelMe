<?php

namespace App\Interfaces;

interface FileMetaInterface
{
    public function columns();
    public function totalRows();
    public function totalCols();
}
