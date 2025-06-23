<?php

namespace App\Http\Requests\Admin\AdminManagement;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

   
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg',
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());;
    }
    protected function store(): array
    {
        return [
            'email' => ['required', 'string', 'email', Rule::unique('admins', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
    public function update(): array
    {
        return [
            'email' => ['required', 'string', 'email', Rule::unique('admins', 'email')->ignore(decrypt($this->route('admin')))],
        ];
    }
}
