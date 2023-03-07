<?php

namespace App\Http\Controllers;

use App\Exports\PostExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
/**
 * System Name : Bulletin Board
 * Module Name : ExcelDownload
 */
class ExcelDownload extends Controller
{
    /**
     * download the posts excels using laravel excel
     * @return Response download excel file
     */
    public function downloadExcel(){
        return Excel::download(new PostExport,'posts.xlsx');
    }
}
