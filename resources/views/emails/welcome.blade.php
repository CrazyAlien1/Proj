@component('mail::message')

Dear {{$user->name}},
click the button bellow to ative your account

<!-- URL::asset('http://proj_dad.teste/api/activateAccount/'.$user->id)-->
@component('mail::button', ['url' => 'http://proj_dad.teste'])
Activate
@endcomponent

Thanks,MemoryGame<br>


@endcomponent
