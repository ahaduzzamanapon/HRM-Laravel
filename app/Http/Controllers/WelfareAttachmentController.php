<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WelfareSupportAttachment;
use App\Models\MedicalSupport;
use App\Models\FuneralSupport;
use App\Models\EmployeeChildrenEducationSupport;
use App\Services\AuthorizationEngine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class WelfareAttachmentController extends Controller
{
    public function download($type, $id)
    {
        $model = match($type) {
            'medical' => MedicalSupport::findOrFail($id),
            'funeral' => FuneralSupport::findOrFail($id),
            'education' => EmployeeChildrenEducationSupport::findOrFail($id),
            default => abort(404),
        };

        $user = Auth::user();
        if (AuthorizationEngine::isEmployeeRole($user) && $model->employee_id != $user->id) {
            abort(403, 'Unauthorized access to attachment.');
        }

        if (empty($model->attachment) || !file_exists(public_path($model->attachment))) {
            abort(404, 'Attachment file not found.');
        }

        return Response::download(public_path($model->attachment));
    }
}
