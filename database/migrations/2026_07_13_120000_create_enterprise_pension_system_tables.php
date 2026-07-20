<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop existing tables to recreate them with the enterprise schema
        Schema::dropIfExists('pension_audit_logs');
        Schema::dropIfExists('pension_policy_versions');
        Schema::dropIfExists('pension_payment_rules');
        Schema::dropIfExists('pension_tax_rules');
        Schema::dropIfExists('pension_family_rules');
        Schema::dropIfExists('pension_medical_rules');
        Schema::dropIfExists('pension_gratuity_rules');
        Schema::dropIfExists('pension_commutation_factors');
        Schema::dropIfExists('pension_commutation_rules');
        Schema::dropIfExists('pension_formula_rules');
        Schema::dropIfExists('pension_eligibility_rules');
        Schema::dropIfExists('pension_schemes');
        Schema::dropIfExists('pension_policies');

        // 1. pension_policies
        Schema::create('pension_policies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->integer('retirement_age')->default(60);
            $table->integer('min_service_years')->default(10);
            $table->integer('max_service_years')->default(40);
            $table->boolean('early_retirement_allowed')->default(false);
            $table->boolean('voluntary_retirement_allowed')->default(false);
            $table->string('calculation_base')->default('Last Basic Salary'); // Last Basic Salary, Last Gross Salary, Average Last 12 Months, Average Last 24 Months, Average Last 36 Months
            $table->decimal('max_pension_percentage', 5, 2)->default(100.00);
            $table->decimal('min_pension_amount', 15, 2)->default(0.00);
            $table->decimal('max_pension_amount', 15, 2)->default(9999999.99);
            $table->string('status')->default('Pending Approval'); // Active, Inactive, Pending Approval, Rejected
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('status');
        });

        // 2. pension_schemes
        Schema::create('pension_schemes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('pension_policies')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type')->default('Defined Benefit'); // General Employee, Officer, Executive, Government, Defined Benefit, Defined Contribution, Hybrid, Provident Fund, Superannuation
            $table->decimal('employee_contribution_percentage', 5, 2)->default(0.00);
            $table->decimal('employer_contribution_percentage', 5, 2)->default(0.00);
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->string('interest_calculation_frequency')->default('N/A'); // Monthly, Quarterly, Semi-Annually, Annually, N/A
            $table->string('status')->default('Active'); // Active, Inactive
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
        });

        // 3. pension_eligibility_rules
        Schema::create('pension_eligibility_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->unique()->constrained('pension_policies')->onDelete('cascade');
            $table->integer('min_service_years')->default(10);
            $table->integer('max_service_years')->default(40);
            $table->integer('min_age')->default(40);
            $table->integer('max_age')->default(80);
            $table->string('employment_type')->nullable(); // Permanent, Contract, etc.
            $table->string('employee_grade')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->unsignedInteger('designation_id')->nullable();
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('set null');
            $table->string('gender')->default('All'); // All, Male, Female, Other
            $table->boolean('is_permanent')->default(true);
            $table->boolean('is_confirmed')->default(true);
            $table->boolean('eligible_for_gratuity')->default(true);
            $table->boolean('eligible_for_family_pension')->default(true);
            $table->boolean('eligible_for_commutation')->default(true);
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. pension_formula_rules
        Schema::create('pension_formula_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('pension_policies')->onDelete('cascade');
            $table->string('name');
            $table->string('code'); // GROSS_PENSION, MONTHLY_PENSION, NET_PENSION, GRATUITY, COMMUTED_AMOUNT, MEDICAL_ALLOWANCE, TAX, NET_PAYABLE
            $table->string('type')->default('Expression'); // Fixed, Percentage, Expression
            $table->string('expression'); // e.g. [gross_pension] * 0.5 * 12 * 10
            $table->text('description')->nullable();
            $table->integer('priority')->default(1);
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['policy_id', 'code']);
        });

        // 5. pension_commutation_rules
        Schema::create('pension_commutation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->unique()->constrained('pension_policies')->onDelete('cascade');
            $table->boolean('enabled')->default(true);
            $table->decimal('max_commutation_percentage', 5, 2)->default(50.00);
            $table->decimal('default_commutation_percentage', 5, 2)->default(50.00);
            $table->boolean('is_age_based')->default(false);
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->decimal('commutation_factor', 5, 2)->nullable();
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. pension_commutation_factors
        Schema::create('pension_commutation_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commutation_rule_id')->constrained('pension_commutation_rules')->onDelete('cascade');
            $table->integer('age');
            $table->decimal('factor', 5, 2);
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['commutation_rule_id', 'age']);
        });

        // 7. pension_gratuity_rules
        Schema::create('pension_gratuity_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->unique()->constrained('pension_policies')->onDelete('cascade');
            $table->boolean('enabled')->default(true);
            $table->string('formula_type')->default('Multiplier Based'); // Fixed Amount, Salary Based, Service Based, Multiplier Based
            $table->string('formula')->nullable();
            $table->decimal('max_amount', 15, 2)->default(9999999.99);
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. pension_medical_rules
        Schema::create('pension_medical_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->unique()->constrained('pension_policies')->onDelete('cascade');
            $table->string('allowance_type')->default('Fixed'); // Fixed, Percentage, Grade Based
            $table->decimal('amount', 15, 2)->default(1500.00);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('grade')->nullable();
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 9. pension_family_rules
        Schema::create('pension_family_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->unique()->constrained('pension_policies')->onDelete('cascade');
            $table->boolean('enabled')->default(true);
            $table->decimal('spouse_percentage', 5, 2)->default(100.00);
            $table->decimal('child_percentage', 5, 2)->default(50.00);
            $table->integer('max_children')->default(2);
            $table->integer('max_child_age')->default(25);
            $table->boolean('disabled_child_lifetime_support')->default(true);
            $table->boolean('widow_lifetime_support')->default(true);
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 10. pension_revision_rules
        Schema::create('pension_revision_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('pension_policies')->onDelete('cascade');
            $table->string('name');
            $table->decimal('revision_percentage', 5, 2)->default(0.00);
            $table->date('effective_date');
            $table->string('circular_reference')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 11. pension_tax_rules
        Schema::create('pension_tax_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->unique()->constrained('pension_policies')->onDelete('cascade');
            $table->boolean('taxable')->default(false);
            $table->string('tax_method')->default('Fixed'); // Fixed, Percentage, Tax Slab
            $table->decimal('tax_percentage', 5, 2)->default(0.00);
            $table->decimal('min_exemption', 15, 2)->default(0.00);
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 12. pension_payment_rules
        Schema::create('pension_payment_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->unique()->constrained('pension_policies')->onDelete('cascade');
            $table->string('payment_frequency')->default('Monthly'); // Monthly, Quarterly, Yearly
            $table->integer('payment_day')->default(1);
            $table->string('payment_method')->default('EFT'); // EFT, BEFTN, RTGS, Bank Transfer, Cash
            $table->boolean('bank_account_required')->default(true);
            $table->decimal('late_payment_interest', 5, 2)->default(0.00);
            $table->string('status')->default('Active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 13. pension_policy_versions
        Schema::create('pension_policy_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('pension_policies')->onDelete('cascade');
            $table->integer('version_number');
            $table->date('effective_date');
            $table->text('change_summary');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 14. pension_audit_logs
        Schema::create('pension_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action'); // Create, Update, Delete, Approve, Reject, Activate, Deactivate
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('pension_audit_logs');
        Schema::dropIfExists('pension_policy_versions');
        Schema::dropIfExists('pension_payment_rules');
        Schema::dropIfExists('pension_tax_rules');
        Schema::dropIfExists('pension_family_rules');
        Schema::dropIfExists('pension_medical_rules');
        Schema::dropIfExists('pension_gratuity_rules');
        Schema::dropIfExists('pension_commutation_factors');
        Schema::dropIfExists('pension_commutation_rules');
        Schema::dropIfExists('pension_formula_rules');
        Schema::dropIfExists('pension_eligibility_rules');
        Schema::dropIfExists('pension_schemes');
        Schema::dropIfExists('pension_policies');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
