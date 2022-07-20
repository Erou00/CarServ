@component('mail::message')
# Introduction

{{$details['commentaire']}}



Merci,<br>
{{ config('app.name') }}
@endcomponent
