<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'teacher_id' => 'nullable|uuid',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'teacher_id.uuid' => 'Teacher ID must be a valid UUID.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'description' => $this->sanitizeDescription($this->description),
        ]);
    }

    private function sanitizeDescription(?string $description): ?string
    {
        return $description ? strip_tags($description) : null;
    }
}
