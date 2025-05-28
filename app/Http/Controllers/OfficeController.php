<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Support\Facades\Route;

class OfficeController extends Controller
{
    public function index()
    {
        return response()->json(Office::all());
    }

    public function show($id)
    {
        return response()->json(Office::findOrFail($id));
    }
}
