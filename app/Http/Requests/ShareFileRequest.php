<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;

class ShareFileRequest extends ActionFileRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'email' => 'required|email',
            ]
        );
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $data = $this->validated();
            $all = $data['all'] ?? false;
            $ids = $data['ids'] ?? [];
            $email = $data['email'] ?? '';

            if (!$all && empty($ids)) {
                $validator->errors()->add('ids', 'Please select files to share.');
            }

            if (!User::where('email', $email)->exists()) {
                $validator->errors()->add('email', "User doesn't exist.");
            }
        });
    }
}
