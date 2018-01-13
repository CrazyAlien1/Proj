@component('mail::message')

Dear {{$user->name}},
click the button bellow to ative your account

@component('mail::button', ['url' => 'http://dadproj.dad'])
Activate
@endcomponent

Thanks,MemoryGame<br>

@endcomponent
