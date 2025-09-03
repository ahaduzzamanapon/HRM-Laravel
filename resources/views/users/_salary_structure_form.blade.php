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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const basicSalaryInput = document.getElementById('basic_salary');
        const allowanceCheckboxes = document.querySelectorAll('input[name^="user_allowances"][name$="[is_enabled]"]');
        const customValueInputs = document.querySelectorAll('input[name^="user_allowances"][name$="[custom_value]"]');
        const grossSalaryDisplay = document.getElementById('gross_salary_display');
        const grossSalaryHidden = document.getElementById('gross_salary_hidden');

        function calculateGrossSalary() {
            let basicSalary = parseFloat(basicSalaryInput.value) || 0;
            let grossSalary = basicSalary;

            // Get global allowance settings (you might need to fetch these via AJAX or pass them from PHP)
            // For now, I'll use a simplified approach assuming allowanceSettings is available in JS
            const allowanceSettings = @json($allowanceSettings);

            allowanceSettings.forEach(function(setting) {
                const allowanceId = setting.id;
                const isEnabledCheckbox = document.getElementById(`allowance_${allowanceId}`);
                const customValueInput = document.querySelector(`input[name="user_allowances[${allowanceId}][custom_value]"]`);

                if (isEnabledCheckbox && isEnabledCheckbox.checked) {
                    let allowanceAmount = 0;
                    let valueToUse = setting.value;

                    if (customValueInput && customValueInput.value !== '') {
                        valueToUse = parseFloat(customValueInput.value);
                    }

                    if (setting.type === 'percentage') {
                        // Simplified HRA for client-side, actual city logic is server-side
                        if (setting.name === 'HRA' && setting.city_specific) {
                            // This client-side calculation won't know the user's city.
                            // It will use the default percentage.
                            // The server-side calculation will be the authoritative one.
                            allowanceAmount = (basicSalary * (valueToUse / 100));
                        } else {
                            allowanceAmount = (basicSalary * (valueToUse / 100));
                        }
                    } else if (setting.type === 'fixed') {
                        allowanceAmount = valueToUse;
                    }

                    // Client-side tax-free limit for Medical Allowance (simplified)
                    if (setting.name === 'Medical Allowance' && setting.tax_free_limit !== null) {
                        const monthlyTaxFreeLimit = setting.tax_free_limit / 12;
                        allowanceAmount = Math.min(allowanceAmount, monthlyTaxFreeLimit);
                    }

                    grossSalary += allowanceAmount;
                }
            });

            grossSalaryDisplay.textContent = grossSalary.toFixed(2);
            grossSalaryHidden.value = grossSalary.toFixed(2);
        }

        basicSalaryInput.addEventListener('input', calculateGrossSalary);
        allowanceCheckboxes.forEach(checkbox => checkbox.addEventListener('change', calculateGrossSalary));
        customValueInputs.forEach(input => input.addEventListener('input', calculateGrossSalary));

        // Initial calculation on page load
        calculateGrossSalary();
    });
</script>