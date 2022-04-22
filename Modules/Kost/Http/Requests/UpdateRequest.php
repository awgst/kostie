<?php

namespace Modules\Kost\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'required',
            'address' => 'required',
            'price' => 'required|numeric',
            'slot' => 'required|numeric',
            'description' => 'required|max:200'
        ];
    }

    /**
     * Get the request data
     */
    public function data()
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'price' => $this->price,
            'slot' => $this->slot,
            'description' => $this->description
        ];
    }
}
