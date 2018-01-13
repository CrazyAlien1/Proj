@component('mail::message')

Dear {{$user->name}},
click the button bellow to ative your account

<<<<<<< HEAD
<!-- URL::asset('http://proj_dad.teste/api/activateAccount/'.$user->id)-->
@component('mail::button', ['url' => 'http://proj_dad.teste'])
=======
@component('mail::button', ['url' => 'http://dadproj.dad'])
>>>>>>> 013d4fcf48a035298c6f245c9fe4845be1b4adda
Activate
@endcomponent

Thanks,MemoryGame<br>


@endcomponent
