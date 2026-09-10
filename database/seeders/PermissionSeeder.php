<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsTree = [
            'staff_management' => [
                'name' => 'Staff Management',
                'children' => [
                    'view_employees' => [
                        'name' => 'Employees',
                        'children' => [
                            'add_employee' => 'Add Employee',
                            'edit_employee' => 'Edit Employee',
                            'delete_employee' => 'Delete Employee',
                            'import_employees' => 'Import Employees',
                        ]
                    ],
                    'employee_departures' => [
                        'name' => 'Departed Employees',
                        'children' => [
                            'view_employee_departures' => 'View Departed Employees',
                            'manage_employee_departures' => 'Manage Departed Employees',
                        ]
                    ],
                ]
            ],
            'organization' => [
                'name' => 'Organization',
                'children' => [
                    'manage_designations' => [
                        'name' => 'Designations',
                        'children' => [
                            'view_designations' => 'View Designations',
                            'add_designation' => 'Add Designation',
                            'edit_designation' => 'Edit Designation',
                            'delete_designation' => 'Delete Designation',
                        ]
                    ],
                    'manage_departments' => [
                        'name' => 'Departments',
                        'children' => [
                            'view_departments' => 'View Departments',
                            'add_department' => 'Add Department',
                            'edit_department' => 'Edit Department',
                            'delete_department' => 'Delete Department',
                        ]
                    ],
                    'rewardings' => [
                        'name' => 'Rewarding',
                        'children' => [
                            'view_rewardings' => 'View Rewarding',
                            'add_rewarding' => 'Add Rewarding',
                            'edit_rewarding' => 'Edit Rewarding',
                            'delete_rewarding' => 'Delete Rewarding',
                        ]
                    ],
                    'innovations' => [
                        'name' => 'Innovations',
                        'children' => [
                            'view_innovations' => 'View Innovations',
                            'add_innovation' => 'Add Innovation',
                            'edit_innovation' => 'Edit Innovation',
                            'delete_innovation' => 'Delete Innovation',
                        ]
                    ],
                    'manage_branches' => [
                        'name' => 'Branch Management',
                        'children' => [
                            'view_branches' => 'View Branches',
                            'add_branch' => 'Add Branch',
                            'edit_branch' => 'Edit Branch',
                            'delete_branch' => 'Delete Branch',
                        ]
                    ],
                ]
            ],
            'hr' => [
                'name' => 'HR',
                'children' => [
                    'upload_attendance_files' => [
                        'name' => 'Attendance File Upload',
                    ],
                    'process_attendance' => [
                        'name' => 'Attendance Process',
                    ],
                    'leave_applications' => [
                        'name' => 'Leave Applications',
                        'children' => [
                            'apply_leave' => 'Apply Leave',
                            'approve_leave' => 'Approve Leave',
                            'reject_leave' => 'Reject Leave',
                        ]
                    ],
                    'movements' => [
                        'name' => 'Movements',
                    ],
                    'smart_movement' => [
                        'name' => 'Smart Movement',
                        'children' => [
                            'view_smart_movement_dashboard' => 'View Movement Dashboard',
                            'view_my_movements' => 'View My Movements',
                            'view_ta_list' => 'View TA List',
                            'view_ta_summary' => 'View TA Summary',
                            'approve_ta' => 'Approve TA',
                        ]
                    ],
                    'manage_holidays' => [
                        'name' => 'Holyday Management',
                    ],
                    'manage_shifts' => [
                        'name' => 'Shift Management',
                    ],
                    'manage_leave_types' => [
                        'name' => 'Leave Types',
                    ],
                ]
            ],
            'payroll' => [
                'name' => 'Payroll & Compliance',
                'children' => [
                    'payroll_process' => [
                        'name' => 'Payroll Process',
                        'children' => [
                            'run_payroll_process' => 'Run Payroll Process',
                            'view_payslips' => 'View Payslips & Reports',
                        ]
                    ],
                    'manage_bonuses' => [
                        'name' => 'Bonus Management',
                        'children' => [
                            'process_bonus' => 'Process Bonus',
                            'bonus_disbursements' => 'Bonus Disbursements',
                        ]
                    ],
                    'tax_management' => [
                        'name' => 'Tax Management',
                        'children' => [
                            'manage_tax_configuration' => 'Manage Tax Configuration',
                            'manage_tax_slabs' => 'Manage Tax Slabs',
                            'employee_tax_profiles' => 'Employee Tax Profiles',
                            'tax_reports' => 'Tax Reports',
                        ]
                    ],
                ]
            ],
            'welfare_fund' => [
                'name' => 'Welfare Fund',
                'children' => [
                    'manage_employee_children_education_supports' => [
                        'name' => 'Children Education Support',
                    ],
                    'manage_funeral_supports' => [
                        'name' => 'Funeral Support',
                    ],
                    'manage_medical_supports' => [
                        'name' => 'Medical Support',
                    ],
                ]
            ],
            'disciplinary_actions' => [
                'name' => 'Disciplinary Actions',
                'children' => [
                    'view_my_departmental_cases' => 'View My Disciplinary Cases & Penalties',
                    'view_departmental_cases' => [
                        'name' => 'Departmental Cases',
                        'children' => [
                            'manage_departmental_cases' => 'Manage Departmental Cases',
                            'add_departmental_cases' => 'Add Departmental Case',
                            'edit_departmental_cases' => 'Edit Departmental Case',
                            'delete_departmental_cases' => 'Delete Departmental Case',
                            'notify_departmental_cases' => 'Notify Employee',
                        ]
                    ],
                    'manage_penalties' => [
                        'name' => 'Penalties',
                    ],
                ]
            ],
            'loans_and_advances' => [
                'name' => 'Loans and Advances',
                'children' => [
                    'manage_loan_types' => [
                        'name' => 'Loan Types',
                    ],
                    'manage_loans' => [
                        'name' => 'Employee Loans',
                        'children' => [
                            'apply_loans' => 'Apply Loans',
                            'edit_loans' => 'Edit Employee Loans',
                            'approve_loans' => 'Approve Loans',
                            'disburse_loans' => 'Disburse Loans',
                            'reject_loans' => 'Reject Loans',
                            'loan_reports' => 'Loan Reports',
                        ]
                    ],
                    'manage_loan_repayments' => [
                        'name' => 'Loan Repayments',
                    ],
                ]
            ],
            'provident_fund' => [
                'name' => 'Provident Fund',
                'children' => [
                    'pf_dashboard' => [
                        'name' => 'PF Dashboard',
                        'children' => [
                            'view_pf_dashboard' => 'View PF Dashboard',
                        ]
                    ],
                    'manage_pf_schemes' => [
                        'name' => 'PF Settings',
                        'children' => [
                            'manage_provident_fund_settings' => 'Manage PF Settings',
                        ]
                    ],
                    'manage_pf_employees' => [
                        'name' => 'PF Employee List',
                    ],
                    'process_pf_contributions' => [
                        'name' => 'Monthly Contributions',
                    ],
                    'view_pf_reports' => [
                        'name' => 'Yearly Reports',
                        'children' => [
                            'view_provident_fund_statements' => 'View PF Statements',
                        ]
                    ],
                    'pf_withdrawals' => [
                        'name' => 'Withdrawal Requests',
                        'children' => [
                            'manage_pf_withdrawals' => 'Manage PF Withdrawals',
                            'manage_pf_settlements' => 'Manage PF Settlements',
                        ]
                    ],
                    'pf_loans' => [
                        'name' => 'PF Loan Applications',
                        'children' => [
                            'approve_pf_loans' => 'Approve PF Loans',
                        ]
                    ],
                    'view_pf_analytics' => [
                        'name' => 'Reports & Analytics',
                        'children' => [
                            'view_all_branches_pf' => 'View All Branches PF',
                        ]
                    ],
                ]
            ],
            'pension' => [
                'name' => 'Pension',
                'children' => [
                    'manage_pension_policies' => [
                        'name' => 'Policies & Schemes',
                    ],
                    'manage_pension_eligibility' => [
                        'name' => 'Eligibility Checks',
                    ],
                    'manage_pension_calculations' => [
                        'name' => 'Pension Calculations',
                    ],
                    'manage_pension_disbursements' => [
                        'name' => 'Pension Disbursements',
                    ],
                    'manage_arrear_bills' => [
                        'name' => 'Arrear Bills',
                    ],
                    'view_pension_reports' => [
                        'name' => 'Pension Reports',
                    ],
                ]
            ],
            'recruitment' => [
                'name' => 'Recruitment',
                'children' => [
                    'manage_recruitment' => [
                        'name' => 'Recruitment Dashboard',
                    ],
                    'manage_recruitment_applications' => [
                        'name' => 'Applications',
                    ],
                    'manage_recruitment_posts' => [
                        'name' => 'Recruitment Posts',
                    ],
                    'manage_career_page' => [
                        'name' => 'Career Page Setup',
                    ],
                ]
            ],
            'biometric' => [
                'name' => 'Biometric',
                'children' => [
                    'manage_biometric_devices' => [
                        'name' => 'Biometric Devices',
                    ],
                    'view_biometric_attendance_logs' => [
                        'name' => 'Attendance Logs',
                    ],
                    'manage_biometric_employee_mappings' => [
                        'name' => 'Employees Mapping',
                    ],
                    'manage_biometric_commands' => [
                        'name' => 'Commands',
                    ],
                ]
            ],
            'inventory' => [
                'name' => 'Inventory',
                'children' => [
                    'manage_asset_categories' => [
                        'name' => 'Asset Categories',
                    ],
                    'manage_assets' => [
                        'name' => 'Assets Management',
                    ],
                    'manage_asset_assignments' => [
                        'name' => 'Asset Assignments',
                    ],
                    'view_asset_logs' => [
                        'name' => 'Asset Audit Logs',
                    ],
                    'view_inventory_reports' => [
                        'name' => 'Inventory Reports',
                    ],
                    'maintenance' => [
                        'name' => 'Maintenance',
                        'children' => [
                            'maintenance_dashboard' => 'Maintenance Dashboard',
                            'manage_maintenance_vendors' => 'Vendors',
                            'manage_maintenance_types' => 'Maintenance Types',
                            'manage_maintenance_requests' => 'Maintenance Requests',
                            'view_maintenance_reports' => 'Maintenance Reports',
                        ]
                    ],
                ]
            ],
            'settings' => [
                'name' => 'Settings',
                'children' => [
                    'manage_site_settings' => [
                        'name' => 'Site Settings',
                    ],
                    'manage_roles_and_permissions' => [
                        'name' => 'Role Management',
                    ],
                    'manage_salaryGrades' => [
                        'name' => 'Salary Grades',
                    ],
                    'manage_allowance_settings' => [
                        'name' => 'Allowance Settings',
                    ],
                    'bankSetups' => [
                        'name' => 'Bank Setups',
                    ],
                    'taxSetups' => [
                        'name' => 'Tax Setups',
                    ],
                    'notices' => [
                        'name' => 'Notices',
                        'children' => [
                            'add_notices' => 'Add Notices',
                        ]
                    ],
                ]
            ],
        ];

        foreach ($permissionsTree as $rootKey => $rootData) {
            $rootPerm = Permission::updateOrCreate(
                ['key' => $rootKey],
                ['name' => $rootData['name'], 'parent_id' => null]
            );

            if (isset($rootData['children'])) {
                foreach ($rootData['children'] as $childKey => $childData) {
                    $childName = is_array($childData) ? $childData['name'] : $childData;
                    $childPerm = Permission::updateOrCreate(
                        ['key' => $childKey],
                        ['name' => $childName, 'parent_id' => $rootPerm->id]
                    );

                    if (is_array($childData) && isset($childData['children'])) {
                        foreach ($childData['children'] as $subKey => $subName) {
                            Permission::updateOrCreate(
                                ['key' => $subKey],
                                ['name' => $subName, 'parent_id' => $childPerm->id]
                            );
                        }
                    }
                }
            }
        }
    }
}
