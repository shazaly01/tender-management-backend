<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalculationOption;
use Illuminate\Http\JsonResponse;

class CalculationOptionController extends Controller
{
    public function index(): JsonResponse
    {
        // إرجاع القائمة كاملة (id و name)
        $options = CalculationOption::select('id', 'name')->get();
        return response()->json(['data' => $options]);
    }
}
