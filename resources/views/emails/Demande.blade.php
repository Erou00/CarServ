@component('mail::message')
# New demande


**{{strtoupper($details['name'])}}**

**Address :** {{$details['adress']}}

**Date :** {{$details['date']}}

**Car :** {{$details['marque']}}

**Phone :** {{$details['tel']}}

### Services :

@foreach ($details['services'] as $item)
✔️  {{$item['name']}}<br>
@endforeach

**Note :** {{$details['comment']}}




@component('mail::button', ['url' => 'http://127.0.0.1:8000/dashboard/demandes/new-demandes'])
Verify
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
