@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => 'http://dadproj.dad'])
Activate
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
