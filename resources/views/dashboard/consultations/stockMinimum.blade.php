@extends('layouts.dashboard.app')

@section('styles')

@endsection
@section('content')
    <section class="content">


          <stock-minimum-page :stocks="{{$stocks}}" :categories="{{$categories}}"
          :pmarques="{{$marques}}" />




    </section>
@endsection


@push('scripts')
<script src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.min.js"></script>
@endpush
