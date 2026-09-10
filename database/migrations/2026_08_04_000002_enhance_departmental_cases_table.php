<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\DepartmentalCase;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departmental_cases', function (Blueprint $table) {
            if (!Schema::hasColumn('departmental_cases', 'case_no')) {
                $table->string('case_no')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('departmental_cases', 'incident_date')) {
                $table->date('incident_date')->nullable()->after('employee_id');
            }
            if (!Schema::hasColumn('departmental_cases', 'status')) {
                $table->string('status')->default('Pending')->after('allegation_category');
            }
            if (!Schema::hasColumn('departmental_cases', 'document')) {
                $table->string('document')->nullable()->after('disciplinary_issue_details');
            }
            if (!Schema::hasColumn('departmental_cases', 'show_cause_date')) {
                $table->date('show_cause_date')->nullable()->after('document');
            }
            if (!Schema::hasColumn('departmental_cases', 'show_cause_explanation')) {
                $table->text('show_cause_explanation')->nullable()->after('show_cause_date');
            }
            if (!Schema::hasColumn('departmental_cases', 'penalty_amount')) {
                $table->decimal('penalty_amount', 10, 2)->nullable()->after('penalty_id');
            }
            if (!Schema::hasColumn('departmental_cases', 'notified_at')) {
                $table->timestamp('notified_at')->nullable()->after('final_action_taken');
            }
        });

        // Backfill case_no for existing cases
        $cases = DepartmentalCase::whereNull('case_no')->orWhere('case_no', '')->get();
        foreach ($cases as $index => $case) {
            $year = $case->created_at ? $case->created_at->format('Y') : date('Y');
            $case->case_no = 'DC-' . $year . '-' . str_pad($case->id, 4, '0', STR_PAD_LEFT);
            $case->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departmental_cases', function (Blueprint $table) {
            $columns = [
                'case_no',
                'incident_date',
                'status',
                'document',
                'show_cause_date',
                'show_cause_explanation',
                'penalty_amount',
                'notified_at'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('departmental_cases', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
