@extends('layouts.dashboard.app')



@section('content')
    <section>
        <div class="container mt-2">
            <form action="{{route('bls.blWithDetails')}}" method="post">
                @csrf


                @include('dashboard.partials._errors')

                <bl-convention-page />

            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>

</script>
@endpush






