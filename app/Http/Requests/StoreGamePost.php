<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGamePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Se o utilizador estiver bloqueado nao pode iniciar jogos multiplayer?
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //Adicionar o: in['singleplayer','multiplayer']
        return [
            'type'=> 'required'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'The game Type is required!',
        ];
    }
}
