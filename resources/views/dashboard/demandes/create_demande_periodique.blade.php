
@extends('layouts.dashboard.app')


@section('content')
    <section>
        <div class="container mt-2">


            <demandes-periodique-page   />


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