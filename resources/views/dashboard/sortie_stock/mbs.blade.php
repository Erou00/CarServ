@extends('layouts.dashboard.app')



@section('content')
    <section>
        <div class="container mt-2">

            <m-bs-page  />

        </div>


    </section>
@endsection

@push('scripts')
    <script>

        $('.select').select2();

    </script>
@endpush
