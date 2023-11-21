<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File; // Assuming you have a File model

class PoolController extends Controller
{
    // ...

    public function showFiles()
    {
        // Retrieve all uploaded files from the database or storage
        $files = File::all(); // Assuming you have a File model and a files table

        // Return the files as a response
        // dd($files); // Dump and die

        // or
        return view('pool.index', ['files' => $files]); // Return as a view
    }

    // ...
}
