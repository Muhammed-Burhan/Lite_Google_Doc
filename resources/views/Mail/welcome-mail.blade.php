
@component("vendor.mail.html.message",['slot'=>'test','subcopy'=>$name])
    Welcome to our app


@component('vendor.mail.html.footer')
    {{ config('app.name') }}
@endcomponent
@endcomponent
