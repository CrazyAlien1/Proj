@component('mail::message')

Dear {{$user->name}},
click the button bellow to ative your account

@component('mail::button', ['url' => 'http://proj_dad.teste'])
Activate
@endcomponent

Thanks,MemoryGame<br>

@endcomponent
