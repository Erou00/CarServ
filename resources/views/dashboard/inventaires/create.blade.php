@extends('layouts.dashboard.app')


@section('content')
    <section>
        <div class="container mt-2">
            <form action="{{route('inventaires.inventaireWithDetails')}}" method="post">
                @csrf

                @include('dashboard.partials._errors')
                <inventaire-page  :numinventaire="{{ $inv }}" />
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
     $(document).ready(function() {
        let tva;
        $(".tva").keyup(function() {
                 tva = $('.tva').val()
                 console.log(tva);
            });
     })
</script>
@endpush
