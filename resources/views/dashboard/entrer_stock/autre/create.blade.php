@extends('layouts.dashboard.app')



@section('content')
<section>
    <div class="container mt-2">
        <form action="{{route('autres.storeWithStock')}}" method="post">
            @csrf


            @include('dashboard.partials._errors')

            <entrer-page :tva="{{$tva}}" />

        </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>

        $('.select').select2();

    </script>
@endpush
