@component('mail::message')
# Introduction

Dear {{$user->name}} , click the link bellow to ative your account

@component('mail::button', ['url' => 'http://proj_dad.teste/'])
Activate
@endcomponent

Thanks,<br>

@endcomponent
