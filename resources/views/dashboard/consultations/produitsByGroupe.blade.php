@extends('layouts.dashboard.app')

@section('styles')

@endsection
@section('content')
    <section class="content">
        <div>
            <h2>Produits par Groupe</h2>
        </div>

        <ul class="breadcrumb mt-3 p-2">
            <li class="breadcrumb-item"><a href="">Acceuil</a></li>
            <li class="breadcrumb-item"><a href="">Consultations</a></li>
            <li class="breadcrumb-item">Produits par Groupe</li>
        </ul>




        <div class="box box-primary">

          <produits-by-groupe-page :groupes="{{$groupes}}" />

        </div><!-- end of box -->




<!-- Button trigger modal -->







    </section>
@endsection


