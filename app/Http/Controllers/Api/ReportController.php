<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function companyStatement(Company $company): JsonResponse
    {
        // تحميل المشاريع مع حساب إجمالي الدفعات لكل مشروع
        $projects = $company->projects()->withSum('payments', 'amount')->get();

        $projectsData = $projects->map(function ($project) {
            $totalPaid = $project->payments_sum_amount ?? 0;
            return [
                'id' => $project->id,
                'name' => $project->name,
                'contract_value' => (float) $project->contract_value,
                'total_paid' => (float) $totalPaid,
                'remaining' => (float) $project->contract_value - $totalPaid,
            ];
        });

        // حساب الإجماليات
        $totalContractsValue = $projectsData->sum('contract_value');
        $totalPaymentsReceived = $projectsData->sum('total_paid');

        return response()->json([
            'data' => [
                'company' => [
                    'id' => $company->id,
                    'name' => $company->name,
                ],
                'projects' => $projectsData,
                'summary' => [
                    'total_contracts_value' => $totalContractsValue,
                    'total_payments_received' => $totalPaymentsReceived,
                    'total_remaining' => $totalContractsValue - $totalPaymentsReceived,
                ]
            ]
        ]);
    }
}
