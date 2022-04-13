<?php

namespace EscolaLms\Lrs\Http\Requests;

use EscolaLms\Lrs\Models\Statement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StatementListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('list', Statement::class);
    }

    public function rules(): array
    {
        return [];
    }
}
