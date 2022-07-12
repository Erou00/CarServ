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
                    <li class="breadcrumb-item active">Mechanics</li>
                </ol>
            </div>
            <h4 class="page-title">ADD Mechanics</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                <form action="{{ isset($mecanicien) ? route('dashboard.mechanics.update',$mecanicien->id) :route('dashboard.mechanics.store') }}" method="post" enctype="multipart/form-data">

                    @csrf
                    @if (isset($mecanicien))
                    @method('PUT')
                    @endif


                    <div class="form-group  row">
                        <label for="" class="col-sm-2 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input class="form-control" class="@error('first_name') is-invalid @enderror"  type="text"
                                name="first_name" value="{{  isset($mecanicien) ? $mecanicien->first_name : ''}}" required>
                                                        </div>
                                                        @error('first_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                      @enderror
                        </div>

                        <div class="form-group  row mt-2">
                            <label for="" class="col-sm-2 col-form-label">Last Name</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('last_name') is-invalid @enderror"  type="text"
                                    name="last_name" value="{{  isset($mecanicien) ? $mecanicien->last_name : ''}}" required>

                                </div>
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                        </div>

                        <div class="form-group  row mt-2">
                            <label for="" class="col-sm-2 col-form-label">CIN</label>
                                <div class="col-sm-8">
                                    <input class="form-control @error('cin') is-invalid @enderror"  type="text" name="cin"
                                    value="{{  isset($mecanicien) ? $mecanicien->cin : ''}}" required>

                                @error('cin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                      @enderror
                            </div>
                        </div>

                        <div class="form-group  row mt-2">
                            <label for="" class="col-sm-2 col-form-label">E-mail</label>
                                <div class="col-sm-8">
                                    <input  class=" form-control @error('email') is-invalid @enderror"  type="email" name="email" required
                                    value="{{  isset($mecanicien) ? $mecanicien->email : ''}}" >
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                  @enderror
                                </div>

                        </div>

                        <div class="form-group  row mt-2">
                            <label for="" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-8">
                                    <input  class="form-control @error('adress') is-invalid @enderror"  type="text" name="adress" required
                                    value="{{  isset($mecanicien) ? $mecanicien->adress : ''}}">

                                @error('adress')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                        @enderror
                                </div>
                            </div>



                            <div class="form-group  row mt-2">
                                <label for="" class="col-sm-2 col-form-label">Phone</label>
                                    <div class="col-sm-8">
                                        <input class="form-control @error('phone_number') is-invalid @enderror" type="text" name="phone_number" required
                                        value="{{  isset($mecanicien) ? $mecanicien->phone_number : ''}}">
                                        @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                  @enderror
                                    </div>
                            </div>

                            @if (!isset($mecanicien))
                            <div class="form-group  row mt-2">
                                <label for="" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control input @error('password') is-invalid @enderror" name="password">
                                        @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                  @enderror
                                    </div>
                            </div>


                            <div class="form-group  row mt-2">
                                <label for="" class="col-sm-2 col-form-label">Confime Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control input @error('password_confirmation') is-invalid @enderror" name="password_confirmation">

                                        @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                  @enderror
                                    </div>
                            </div>

                            @endif


                            <div class="form-group  row">

                                    <div class="col-sm-10">
                                            <input class="btn btn-dark pull-right my-2" type="submit" value="Save"  />
                                    </div>
                            </div>

                </form><!-- end of form -->



            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->


@endsection



@section('scripts')
{{-- CKEditor CDN --}}

<script>
        $(document).ready(function () {

            ClassicEditor
.create( document.querySelector( '.ckeditor' ) )
.catch( error => {
console.error( error );
} );


$(".image").change(function () {

if (this.files && this.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('.image-preview').attr('src', e.target.result);
    }

    reader.readAsDataURL(this.files[0]);
}

});


});//end of ready

</script>

@endsection
