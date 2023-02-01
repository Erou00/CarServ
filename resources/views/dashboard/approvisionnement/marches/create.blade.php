@extends('layouts.dashboard.app')



@section('content')
    <section>
        <div class="container mt-2">
            <form action="{{route('marches.storeWithStock')}}" method="post">
                @csrf

                @include('dashboard.partials._errors')

                <marches-page :tva="{{$tva}}" />
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
