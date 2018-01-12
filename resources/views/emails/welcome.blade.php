@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => 'http://proj_dad.teste'])
Activate
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
