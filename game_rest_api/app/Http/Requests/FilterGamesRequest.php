<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterGamesRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "status" => "sometimes|nullable|in:playing,interrupted,ended",
            "player" => "sometimes|nullable|integer|exists:users,id",
            "secondplayer" =>
                "sometimes|nullable|integer|different:player|exists:users,id",
            "winner" => "sometimes|nullable|integer|exists:users,id",
        ];
    }
}
