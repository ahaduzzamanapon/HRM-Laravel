<?php

$files = [
    'resources/views/bank_setups/table.blade.php',
    'resources/views/branches/table.blade.php',
    'resources/views/departmental_cases/table.blade.php',
    'resources/views/departments/table.blade.php',
    'resources/views/designations/table.blade.php',
    'resources/views/employee_children_education_supports/table.blade.php',
    'resources/views/funeral_supports/table.blade.php',
    'resources/views/holydays/table.blade.php',
    'resources/views/innovations/table.blade.php',
    'resources/views/loans/table.blade.php',
    'resources/views/loan_repayments/table.blade.php',
    'resources/views/loan_types/table.blade.php',
    'resources/views/medical_supports/table.blade.php',
    'resources/views/notices/table.blade.php',
    'resources/views/penalties/table.blade.php',
    'resources/views/permissions/table.blade.php',
    'resources/views/provident_funds/table.blade.php',
    'resources/views/rewardings/table.blade.php',
    'resources/views/role_and_permissions/table.blade.php',
    'resources/views/salary_grades/table.blade.php',
    'resources/views/site_settings/table.blade.php',
    'resources/views/tax_setups/table.blade.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "File not found: $file\n";
        continue;
    }

    $content = file_get_contents($file);

    // Regex to match the <td> that contains the action buttons
    // Most files have this pattern:
    // <td>
    //   {!! Form::open(...) !!} or <div class='btn-group'>
    //   ... btn-outline-primary ...
    //   ... Form::close() ...
    // </td>
    
    // We can extract the route and variable from the view link
    preg_match("/route\('([^']+)\.show', \[\\$([a-zA-Z0-9_]+)->id\]\)/", $content, $matches);
    
    if (!$matches) {
        preg_match("/route\('([^']+)\.edit', \[\\$([a-zA-Z0-9_]+)->id\]\)/", $content, $matches);
    }

    if ($matches) {
        $routePrefix = $matches[1];
        $varName = $matches[2];
        
        $replacement = "                <td>\n                    @include('layouts.partials.action_buttons', [\n                        'viewRoute' => route('{$routePrefix}.show', [\${$varName}->id]),\n                        'editRoute' => route('{$routePrefix}.edit', [\${$varName}->id]),\n                        'deleteRoute' => route('{$routePrefix}.destroy', [\${$varName}->id]),\n                    ])\n                </td>";

        // Find the <td> containing the btn-group
        // It starts with <td> or <td ...> and ends with </td>
        // And it must contain "btn-group" or "btn-outline-primary"
        
        $newContent = preg_replace(
            "/<td>\s*(?:\{!! Form::open.*?!!\}\s*)?(?:@if.*?can.*?\s*)?<div class='btn-group'>.*?<\/div>\s*(?:\{!! Form::close.*?!!\}\s*)?(?:@endif\s*)?<\/td>/s",
            $replacement,
            $content,
            1,
            $count
        );
        
        if ($count == 0) {
            // Try alternate pattern where Form::open is outside
            $newContent = preg_replace(
                "/<td>\s*\{!! Form::open.*?!!\}\s*<div class='btn-group'>.*?<\/div>\s*\{!! Form::close.*?!!\}\s*<\/td>/s",
                $replacement,
                $content,
                1,
                $count
            );
        }
        
        if ($count == 0) {
            // Try another alternate pattern (e.g. users table)
            // Or just a general pattern for <td> with btn-group and route(
            $newContent = preg_replace(
                "/<td>(?=.*btn-outline-primary).*?<\/td>/s",
                $replacement,
                $content,
                1,
                $count
            );
        }

        if ($count > 0) {
            file_put_contents($file, $newContent);
            echo "Updated: $file\n";
        } else {
            echo "Failed to match: $file\n";
        }
    } else {
        echo "Could not find route/var in: $file\n";
    }
}
