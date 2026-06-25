<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:job_posts,slug,' . ($this->route('job_post') ?? $this->route('jobPost') ?? null),
            'description' => 'required|string',
            'location'    => 'nullable|string|max:255',
            'status'      => 'required|in:draft,published,closed',
            'deadline'    => 'nullable|date',
        ];
    }
}
