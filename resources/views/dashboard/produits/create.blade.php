@extends('layouts.dashboard.app')



@section('content')
    <section>
        <div class="container mt-2">
            <form action="{{route('produits.store')}}" method="post">
                @csrf
                <div class="row mb-0">
                    <div class="col-md-3 offset-md-9 formButton" style="text-align: end;">
                        <button class="btn btn-default" type="submit" id="submit">
                            <i class="fa fa-floppy-o me-1" aria-hidden="true"></i>Enregistrer</button>
                    </div>
                </div>
                @include('dashboard.partials._errors')
                <produit-create :categories="{{$categories}}"
                :marques="{{$marques}}" :unitereglementaires="{{$uniteReglementaires}}"
                :devises="{{$devises}}" :groupes="{{$groupes}}" />
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
