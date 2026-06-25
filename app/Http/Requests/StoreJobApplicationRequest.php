<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Anyone can apply
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'job_post_id'  => 'required|exists:job_posts,id',
            'first_name'   => 'required|string|max:50',
            'last_name'    => 'required|string|max:50',
            'email'        => 'required|email|max:100',
            'phone'        => 'nullable|string|max:20',
            'cover_letter' => 'nullable|string',
            'resume'       => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ];
    }
}
