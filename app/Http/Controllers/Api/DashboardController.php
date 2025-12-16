<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $companiesCount = Company::count();
        $projectsCount = Project::count();
        $totalContractsValue = Project::sum('contract_value');
        $totalPaymentsValue = Payment::sum('amount');

        return response()->json([
            'data' => [
                'companies_count' => $companiesCount,
                'projects_count' => $projectsCount,
                'total_contracts_value' => (float) $totalContractsValue,
                'total_payments_value' => (float) $totalPaymentsValue,
            ]
        ]);
    }
}
