{!! Form::model($users, ['route' => ['users.updateSalary', $users->id], 'method' => 'patch']) !!}
<div class="row">
    <div class="col-md-12">
        <h3>Salary Structure</h3>
        <p>Define the basic salary and manage active allowances for this user.</p>

        <!-- Basic Salary Input -->
        <div class="form-group row border-bottom pb-2 mb-2">
            <label class="col-sm-4 col-form-label">Basic Salary</label>
            <div class="col-sm-8">
                <input type="number" step="0.01" class="form-control" id="basic_salary" name="basic_salary" value="{{ $users->basic_salary ?? 0 }}" placeholder="Enter Basic Salary">
            </div>
        </div>

        <h4>Allowances</h4>
        @foreach($allowanceSettings as $allowanceSetting)
            @php
                $userAllowance = $users->userAllowances->where('allowance_setting_id', $allowanceSetting->id)->first();
                $isEnabled = $userAllowance ? $userAllowance->is_enabled : false;
                $customValue = $userAllowance ? $userAllowance->custom_value : '';
            @endphp

            <div class="form-group row border-bottom pb-2 mb-2">
                <label class="col-sm-4 col-form-label">{{ $allowanceSetting->name }} ({{ ucfirst($allowanceSetting->type) }})</label>
                <div class="col-sm-8">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="allowance_{{ $allowanceSetting->id }}" name="user_allowances[{{ $allowanceSetting->id }}][is_enabled]" value="1" {{ $isEnabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="allowance_{{ $allowanceSetting->id }}">Enabled</label>
                    </div>

                    @if($allowanceSetting->type == 'percentage' || $allowanceSetting->type == 'fixed')
                        <div class="input-group mt-2" style="width: 200px;">
                            <input type="number" step="0.01" class="form-control" name="user_allowances[{{ $allowanceSetting->id }}][custom_value]" value="{{ $customValue }}" placeholder="Custom Value">
                            @if($allowanceSetting->type == 'percentage')
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            @endif
                        </div>
                        <small class="form-text text-muted">
                            Leave empty to use global setting ({{ $allowanceSetting->value }}{{ $allowanceSetting->type == 'percentage' ? '%' : '' }}).
                            @if($allowanceSetting->tax_free_limit)
                                Tax-free limit: {{ $allowanceSetting->tax_free_limit }}
                            @endif
                            @if($allowanceSetting->city_specific)
                                (Dhaka: {{ $allowanceSetting->city_value }}%)
                            @endif
                        </small>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Gross Salary Display -->
        <div class="form-group row border-top pt-3 mt-3">
            <label class="col-sm-4 col-form-label">Gross Salary</label>
            <div class="col-sm-8">
                <p class="form-control-static" id="gross_salary_display">{{ $users->gross_salary ?? 0 }}</p>
                <input type="hidden" name="gross_salary" id="gross_salary_hidden" value="{{ $users->gross_salary ?? 0 }}">
            </div>
        </div>

        <div class="form-group col-sm-12" style="text-align-last: right;">
            {!! Form::submit('Save Salary Structure', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const basicSalaryInput = document.getElementById('basic_salary');
        const allowanceCheckboxes = document.querySelectorAll('input[name^="user_allowances"][name$="[is_enabled]"]');
        const customValueInputs = document.querySelectorAll('input[name^="user_allowances"][name$="[custom_value]"]');
        const grossSalaryDisplay = document.getElementById('gross_salary_display');
        const grossSalaryHidden = document.getElementById('gross_salary_hidden');
        const allowanceSettings = @json($allowanceSettings);

        /**
         * Calculates the gross salary based on the basic salary and enabled allowances.
         */
        function calculateGrossSalary() {
            let basicSalary = parseFloat(basicSalaryInput.value) || 0;
            let grossSalary = basicSalary;

            allowanceSettings.forEach(function(setting) {
                const isEnabledCheckbox = document.getElementById(`allowance_${setting.id}`);
                if (isEnabledCheckbox && isEnabledCheckbox.checked) {
                    grossSalary += getAllowanceAmount(setting, basicSalary);
                }
            });

            grossSalaryDisplay.textContent = grossSalary.toFixed(2);
            grossSalaryHidden.value = grossSalary.toFixed(2);
        }

        /**
         * Calculates the amount for a single allowance.
         * @param {object} setting - The allowance setting object.
         * @param {number} basicSalary - The basic salary.
         * @returns {number} The calculated allowance amount.
         */
        function getAllowanceAmount(setting, basicSalary) {
            const customValueInput = document.querySelector(`input[name="user_allowances[${setting.id}][custom_value]"]`);
            let valueToUse = setting.value;

            if (customValueInput && customValueInput.value !== '') {
                valueToUse = parseFloat(customValueInput.value);
            }

            let allowanceAmount = 0;
            if (setting.type === 'percentage') {
                // Note: The city-specific HRA logic is handled on the server-side.
                // This client-side calculation uses the default percentage.
                allowanceAmount = (basicSalary * (valueToUse / 100));
            } else if (setting.type === 'fixed') {
                allowanceAmount = valueToUse;
            }

            // Note: The tax-free limit for Medical Allowance is a simplified calculation on the client-side.
            // The server-side calculation is the authoritative one.
            if (setting.name === 'Medical Allowance' && setting.tax_free_limit !== null) {
                const monthlyTaxFreeLimit = setting.tax_free_limit / 12;
                allowanceAmount = Math.min(allowanceAmount, monthlyTaxFreeLimit);
            }

            return allowanceAmount;
        }

        basicSalaryInput.addEventListener('input', calculateGrossSalary);
        allowanceCheckboxes.forEach(checkbox => checkbox.addEventListener('change', calculateGrossSalary));
        customValueInputs.forEach(input => input.addEventListener('input', calculateGrossSalary));

        // Initial calculation on page load
        calculateGrossSalary();
    });
</script>
