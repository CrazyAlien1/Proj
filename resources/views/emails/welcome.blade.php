@component('mail::message')

Dear {{$user->name}},
click the button bellow to ative your account

@component('mail::button', ['url' => URL::asset('http://proj_dad.teste/api/activateAccount/'.$user->id)])
Activate
@endcomponent

Thanks,MemoryGame<br>

@endcomponent
