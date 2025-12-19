<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{

/**
     * تقرير عام: ملخص مالي لكل الشركات
     * يعرض: عدد المشاريع، إجمالي العقود، المدفوع، والمتبقي لكل شركة
     */
    public function companiesSummary(): JsonResponse
    {
        // جلب الشركات مع عدد المشاريع ومجموع مبالغ العقود
        // وجلب مجموع الدفعات من خلال المشاريع
        $companies = Company::withCount('projects')
            ->withSum('projects as total_contracts_value', 'contract_value')
            ->get();

        $data = $companies->map(function ($company) {
            // حساب إجمالي المدفوعات لكل مشاريع هذه الشركة
            // نستخدم pluck ثم sum لضمان الدقة المالية
            $totalPaid = $company->projects()->withSum('payments', 'amount')->get()->sum('payments_sum_amount');

            $contractsValue = (float) ($company->total_contracts_value ?? 0);
            $paidValue = (float) ($totalPaid ?? 0);

            return [
                'id' => $company->id, // DECIMAL(18, 0)
                'name' => $company->name,
                'license_number' => $company->license_number, // رقم الرخصة التجارية
                'tax_number' => $company->tax_number,
                'projects_count' => $company->projects_count,
                'total_contracts_value' => $contractsValue,
                'total_paid' => $paidValue,
                'total_remaining' => $contractsValue - $paidValue,
            ];
        });

        return response()->json([
            'data' => $data,
            'grand_summary' => [
                'total_companies' => $data->count(),
                'total_projects' => $data->sum('projects_count'),
                'grand_total_value' => $data->sum('total_contracts_value'),
                'grand_total_paid' => $data->sum('total_paid'),
                'grand_total_remaining' => $data->sum('total_remaining'),
            ]
        ]);
    }

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
                    // === تمت إضافة البيانات المفقودة هنا ===
                    'tax_number' => $company->tax_number,
                    'license_number' => $company->license_number,
                    'owner_name' => $company->owner_name,
                    'address' => $company->address,
                    // ======================================
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
