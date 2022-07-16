@extends('dashboard.layouts.app')


@section('styles')

<link href="{{asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">



@endsection

@section('content')

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Car service</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Demandes</li>
                </ol>
            </div>
            <h4 class="page-title">Demandes</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card" >
            <div class="card-body">
                <div class="row mb-2">

                </div>

                <form method="post" class="mt-5" action="{{  route('dashboard.updateDemandeByAdminPost',$demande->id) }}">

                    @csrf

                        <div class="row">
                            <div class="col-md">
                                <div class="form-group row">
                                    <label for="Email" class="col-md-5 col-form-label"><strong>Choose a car</strong></label>
                                    <div class="col-md-7">
                                        <select class="form-select form-control mb-2" name="car_id" id="marque"
                                        data-dependent="model" aria-label="Default select example" required >
                                        <option option value="" >Choisissez</option>
                                        @foreach ($demande->user->cars as $car)
                                        <option value="{{$car->id}}" {{$car->id == $demande->car_id ? 'selected': ''}}>{{$car->marque->name}}</option>
                                        @endforeach
                                        </select>
                                     </div>
                                  </div>

                                  <div class="form-group row">
                                    <label for="ddemande" class="col-md-5 col-form-label"><strong>Date</strong></label>
                                    <div class="col-md-7">
                                      <input type="datetime-local" class="form-control mb-2" id="ddemande" name="date" value="{{ $demande->date}}" required>
                                      @error('date')
                                      <span class="invalid-feedback" role="alert">
                                          <strong style="font-size: 24px">{{ $message }}</strong>
                                      </span>
                                      @enderror
                                    </div>
                                  </div>

                                  <div class="form-group row">
                                    <label for="adress" class="col-md-5 col-form-label"><strong>Address</strong></label>
                                    <div class="col-md-7">
                                        <textarea name="address" class="form-control mb-2" id="" cols="30" rows="3" required>{{$demande->user->adress}}</textarea>

                                    </div>
                                  </div>




                                   @if ($services->count()>0)
                                  @foreach ($services as $item)
                                  <div class="form-check m-4">
                                    <input  class="check-toggle form-check-input"
                                        @foreach ($demande->services as $ser)
                                            @if ($ser->id == $item->id )
                                                checked

                                            @endif
                                        @endforeach
                                    type="checkbox" name="services_id[]" value="{{$item->id}}" id="defaultCheck{{$item->id}}">
                                    <label class="form-check-label" for="defaultCheck{{$item->id}}">
                                      <strong>{{$item->name}}</strong>
                                    </label>

                                  </div>
                                  @endforeach
                                  @endif






                                  <div class="form-check m-4">
                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong style="font-size: 24px">{{ $message }}</strong>
                                    </span>
                                   @enderror

                                   @error('products_id')
                                   <span class="invalid-feedback" role="alert">
                                       <strong style="font-size: 24px">{{ $message }}</strong>
                                   </span>
                                  @enderror
                                  </div>



                            </div>

                            <div class="col-md">


                                  <div class="form-group row" >


                                        <label for="Email" class="col-md-12 col-form-label">
                                            <strong>Comment: </strong></label>

                                        <div class="col-md-9">
                                            <textarea class="form-control  mb-2" id="exampleFormControlTextarea1" rows="4" cols="18" name="comment"></textarea>

                                        </div>

                                    </div>







                                    <div class="row">
                                        <div class="col-md-9 ">
                                            <button type="submit" class="btn btn-dark btn-lg pull-right mb-5">
                                                {{ __('Update') }}
                                            </button>
                                        </div>
                                      </div>

                            </div>

                          </div>






                    </form>


            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->



@endsection



@section('scripts')
<!-- plugin js -->
<script src="{{asset('assets/js/vendor/dropzone.min.js')}}"></script>
<!-- init js -->
<script src="{{asset('assets/js/ui/component.fileupload.js')}}"></script>

     <script src="{{asset('assets/js/vendor/jquery.dataTables.min.js')}}"></script>
     <script src="{{asset('assets/js/vendor/dataTables.bootstrap5.js')}}"></script>
     <script src="{{asset('assets/js/vendor/dataTables.responsive.min.js')}}"></script>
     <script src="{{asset('assets/js/vendor/responsive.bootstrap5.min.js')}}"></script>

     <!-- third party js ends -->

     <!-- demo app -->
     <script src="{{asset('assets/js/pages/demo.datatable-init.js')}}"></script>


@endsection
