<?php

namespace App\Http\Requests\Api;

use App\Models\Ticket;
use App\Rules\E164Phone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', new E164Phone],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
            'attachments'   => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $email = $this->input('email');
            $phone = $this->input('phone');
            if (!$email && !$phone) {
                return;
            }

            $since = Carbon::now()->subDay();

            $exists = Ticket::query()
                ->where('created_at', '>=', $since)
                ->whereHas('customer', function ($query) use ($email, $phone) {
                    $query->when($email, fn ($q) => $q->where('email', $email));
                    if ($phone) {
                        $query->when(
                            $email,
                            fn ($q) => $q->orWhere('phone', $phone),
                            fn ($q) => $q->where('phone', $phone),
                        );
                    }
                })
                ->exists();

            if ($exists) {
                $field = $email ? 'email' : 'phone';

                $validator->errors()->add(
                    $field,
                    'You can send only one request per day from the same email or phone number.'
                );
            }
        });
    }
}
