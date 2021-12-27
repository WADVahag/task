<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

use Illuminate\Support\Str;

class ArticleCreateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:191',
            'content' => 'required|string|max:5000',
            'tags' => 'required|array|max:100',
            'tags.*' => 'required|string|max:191'
        ];
    }

    /**
     *  Get lower-case converted tags, remove duplicates 
     * 
     *  @return Collection
     */
    public function getLowerCaseTags(): Collection
    {
        return collect($this->get('tags', []))->map(function ($tag) {
            return Str::lower($tag);
        })->unique();
    }
}
