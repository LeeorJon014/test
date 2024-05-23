@component('mail::message')
# Greetings!

The Email you provided: {{ $email }}

The Password provided: {{ $password }}

Please click the button below to verify your email address before you can access the system.

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent