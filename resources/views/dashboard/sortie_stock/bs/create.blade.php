@extends('layouts.dashboard.app')



@section('content')
    <section>
        <div class="container mt-2">
            <form action="{{route('bs.bsWithDetails')}}" method="post">
                @csrf


                @include('dashboard.partials._errors')

            <bs-page  />

        </form>
    </div>
</section>
@endsection

@push('scripts')

@endpush






