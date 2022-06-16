@extends('layouts.app')
@section('styles')
    <style>

   label{
    font-weight: bold;
        color: black;
   }
    select{
        height: 56px;
        font-weight: bold;
        color: black;
        background: white;
    }

    .form-control:read-only {
    background-color: #fff;
    opacity: 1;
    font-weight: bold
}







    </style>
@endsection
@section('content')
<div class="container mt-5">
    <div class="main-body">


            <form class="" method="POST" action="{{isset($vehicule) ? route('cars.update',$vehicule) : route('cars.store')}}" enctype="multipart/form-data">
                <div class="row">

                    @csrf
                    @if (isset($vehicule))
                        @method('PUT')
                    @endif
                        <div class="col-lg-6">


                            <div class="mb-3 row">
                                <label for="marque" class="col-md-3 col-form-label">Mark</label>
                                <div class="col-md-9">
                                <select class="form-select form-control dynamic" name="marque_id" id="marque"
                                data-dependent="model" aria-label="Default select example" required >
                                <option option value="" >Choose</option>
                                @foreach ($marques as $marque)
                                <option  {{ (isset($vehicule) && ($vehicule->marque_id == $marque ->id )? "selected":"")}}  value="{{$marque->id}}">{{$marque->name}}</option>
                                @endforeach
                                </select>
                                @error('marque_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                         </div>

                            <div class="mb-3 row">
                                <label for="model" class="col-md-3 col-form-label">Model</label>
                            <div class="col-md-9">
                                <select name="model_id" id="model" class="form-control" data-dependent="model" required
                                aria-label="Default select example" style="appearance: auto;">

                                   </select>
                                   @error('model_id')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror
                            </div>
                            </div>


                            <div class="mb-3 row">
                                <label for="model-year" class="col-md-3 col-form-label">Year</label>
                            <div class="col-md-9">
                                <select name="year" id="model-year" class="form-control input-lg " data-dependent="model-year" required style="appearance: auto;">
                                    <option option value="">Choose</option>

                                    {{ $last=  now()->year - 40 }}
                                    {{ $now = now()->year }}

                                      @for ($i = $now; $i >= $last; $i--)
                                         <option {{ (isset($vehicule) && ($vehicule->year == $i )? "selected":"")}} value="{{ $i }}">{{ $i }}</option>
                                      @endfor

                                    <option value="{{$last-1}}">1980 or older</option>
                                   </select>

                                   @error('annee')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror

                            </div>
                            </div>

                            <div class="mb-3 row">
                            <div class="col-md-9 offset-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input"
                                     {{((isset($vehicule) && $vehicule->for_sale) || $errors->get('origin_id') || $errors->get('fiscal_power') || $errors->get('kilo')|| $errors->get('doors')|| $errors->get('first_hand') || $errors->get('gear_box')) ? 'checked' : ''}}
                                    value="true" name="for_sale" id="forsale">
                                    <label class="form-check-label" for="exampleCheck1">For Sale</label>
                                  </div>
                            </div>
                            </div>


                            <div class="mb-3 row sale">
                                <label for="model" class="col-md-3 col-form-label">Origin</label>
                            <div class="col-md-9">
                                <select name="origin_id" id="origin" class="form-control" data-dependent="origin"
                                aria-label="Default select example" style="appearance: auto;">

                                <option option value="" >Choose</option>
                                @foreach ($origins as $origin)
                                <option  {{ (isset($vehicule) && ($vehicule->origin == $origin ->id )? "selected":"")}}  value="{{$origin->id}}">{{$origin->origin}}</option>
                                @endforeach

                                   </select>
                                   @error('origin_id')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror
                            </div>
                            </div>



                            <div class="mb-3 row sale">
                                <label for="model" class="col-md-3 col-form-label">Kilometres</label>
                            <div class="col-md-9">
                                <select name="kilo" id="model" class="form-control" data-dependent="kilo"
                                aria-label="Default select example" style="appearance: auto;">
                                <option option value="" >Choose</option>
                                @foreach ($kilometers as $kilo)
                                <option  {{ (isset($vehicule) && ($vehicule->kilometres == $kilo ->id )? "selected":"")}}  value="{{$kilo->id}}">{{$kilo->kilometers}}</option>
                                @endforeach
                                   </select>
                                   @error('kilo')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror
                            </div>
                            </div>

                            <div class="mb-3 row sale">
                                <label for="model" class="col-md-3 col-form-label">First Hand</label>
                            <div class="col-md-9">
                                <select name="first_hand" id="first_hand" class="form-control" data-dependent="first_hand"
                                aria-label="Default select example" style="appearance: auto;">

                                    <option option value="" >Choose</option>

                                    <option    value="yes">Yes</option>
                                    <option   value="no">No</option>


                                   </select>
                                   @error('first_hand')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror
                            </div>
                            </div>



                        </div>

                        <div class="col-lg-6">


                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-md-3 col-form-label">Carbirant</label>
                            <div class="col-md-9">
                                <select class="form-select form-control" aria-label="Default select example" name="carbirant_id" required >
                                    <option option value="" >Choose</option>
                                    @foreach ($carbirants as $carbirant)
                                    <option {{ (isset($vehicule) && ($vehicule->carbirant_id == $carbirant ->id )? "selected":"")}} value="{{$carbirant->id}}">{{$carbirant->type}}</option>
                                    @endforeach
                                </select>
                                @error('carbirant_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-md-3 col-form-label">Carte grise</label>
                            <div class="col-md-9">
                                <input type="file" class="form-select form-control" name="carte_grise_front" multiple {{ (isset($vehicule) ? "":"required")}}>
                                @error('carte_grise_front')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            </div>

                             <div class="mb-3 row">
                             <label for="staticEmail" class="col-md-3 col-form-label">Carte grise</label>
                             <div class="col-md-9">
                             <input type="file" class="form-select form-control" name="carte_grise_back" multiple {{ (isset($vehicule) ? "":"required")}}>
                             @error('carte_grise_back')
                             <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                             </span>
                             @enderror
                             </div>
                             </div>

                             @if (isset($vehicule) )
                                <div class="row mb-3">
                                <div class="col-md-6">
                                <img src="{{asset('uploads/carte_grise/'.$vehicule->carte_grise_front)}}" alt="" width="100%" height="150" srcset="">
                                </div>
                                <div class="col-md-6">
                                    <img src="{{asset('uploads/carte_grise/'.$vehicule->carte_grise_back)}}" alt="" width="100%" height="150" srcset="">
                                    </div>

                             </div>
                             @endif



                             <div class="mb-3 row sale">
                                <label for="model" class="col-md-3 col-form-label">Fiscal Power</label>
                            <div class="col-md-9">
                                <select name="fiscal_power" id="model" class="form-control" data-dependent="fiscal_power"
                                aria-label="Default select example" style="appearance: auto;">>

                                <option value="">choose</option>


                                @for ($i = 4; $i < 42; $i++)
                                <option {{ (isset($vehicule) && ($vehicule->fiscal_power == $i )? "selected":"")}} value="{{$i}}">{{$i.' HS'}}</option>
                                @endfor

                                <option value="42">Over 41 HS</option>
                                   </select>
                                   @error('fiscal_power')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror
                            </div>
                            </div>



                            <div class="mb-3 row sale">
                                <label for="model" class="col-md-3 col-form-label">Gear Box</label>
                            <div class="col-md-9">
                                <select name="gear_box" id="grar_box" class="form-control" data-dependent="grar_box"
                                aria-label="Default select example" style="appearance: auto;">>
                                <option value="">choose</option>
                                <option value="automatique">Automatique</option>
                                <option value="manual">Manual</option>
                                   </select>
                                   @error('gear_box')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror
                            </div>
                            </div>


                            <div class="mb-3 row sale">
                                <label for="model" class="col-md-3 col-form-label">Doors</label>
                            <div class="col-md-9">
                                <select name="doors" id="model" class="form-control" data-dependent="doors"
                                aria-label="Default select example" style="appearance: auto;">>


                                        <option value="">choose</option>
                                        <option value="3">3</option>
                                        <option value="5">5</option>
                                   </select>
                                   @error('doors')
                                   <span class="invalid-feedback" role="alert">
                                       <strong>{{ $message }}</strong>
                                   </span>
                                   @enderror
                            </div>
                            </div>





                        </div>

                <button class="btn btn-primary" type="submit">Submit form</button>

                 </div>
              </form>



    </div>
</div>
@endsection


@section('scripts')


<script>
$(".sale").hide();
@if ( (isset($vehicule) && $vehicule->for_sale) || $errors->get('origin_id') || $errors->get('fiscal_power') || $errors->get('kilo')|| $errors->get('doors')|| $errors->get('first_hand') || $errors->get('gear_box') )
$(".sale").show(300);
@endif
$("#forsale").click(function() {
    if($(this).is(":checked")) {
        $(".sale").show(300);
    } else {
        $(".sale").hide(200);
    }
});

    $(document).ready(function(){
            $('.dynamic').change(function(){
                console.log('work');
                if($(this).val() != '')
                {
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = "{{ csrf_token() }}";

                console.log(select);
                console.log(value);
                console.log(dependent);
                $.ajax({
                    url:"{{route('getModel')}}",
                    method:"POST",
                    data:{select:select, value:value, _token:_token, dependent:dependent},
                    success:function(result)
                    {
                    $('#'+dependent).html(result);
                    }
                })
                }
                });

                $('#marque').change(function(){
                    $('#model').val('');

                });

    })
</script>
@endsection
