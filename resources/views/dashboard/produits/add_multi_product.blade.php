@extends('layouts.dashboard.app')

@section('styles')
    <style>
        .form-control {
            border-color: #000;
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
{{-- {{dd($categories)}} --}}
<produits-page  :categories="{{$categories}}"
                :marques="{{$marques}}" :unitereglementaires="{{$uniteReglementaires}}"
                :devises="{{$devises}}" :groupes="{{$groupes}}"/>



@endsection

@push('scripts')
    <script>





    </script>
@endpush
