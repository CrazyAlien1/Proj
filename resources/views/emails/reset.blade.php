@component('mail::message')

    Dear {{$user->name}},
    there is a button to make possible to reset the password

@component('mail::button', ['url' => 'http://proj_dad.teste/reset'])
Reset
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
