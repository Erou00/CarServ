@extends('layouts.app')
@section('styles')
<script src="https://kit.fontawesome.com/39e20eb3b4.js" crossorigin="anonymous"></script>
<style>


</style>
@endsection
@section('content')
<div class="container mt-5 car" >
    <div class="main-body">


        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="container ">
                            <div class="row" id="user-profile">
                                <div class="col-lg-3 col-md-4 col-sm-12">
                                    <div class="main-box clearfix">
                                        <h2>{{ ucfirst($service->name)}}</h2>
                                        <img src="{{asset('uploads/services_images/'.$service->image)}}" width="180" alt="" class="profile-img img-responsive center-block">



                        {{--
                                        <div class="profile-message-btn center-block text-center">
                                            <a href="#" class="btn btn-success">
                                                <i class="fa fa-envelope"></i> Send message
                                            </a>
                                        </div> --}}
                                    </div>
                                </div>

                                <div class="col-lg-9 col-md-8 col-sm-12">
                                    <div class="main-box clearfix">



                                            <div class="profile-header">
                                                <h3><span>Info</span></h3>
                                            </div>


                                            <div class="row profile-user-info mt-2">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-6 profile-user-details clearfix">
                                                            <div class="profile-user-details-label">
                                                                Description
                                                             </div>
                                                             <div class="profile-user-details-value">
                                                                 {!! $service->description !!}
                                                             </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row profile-user-info mt-2">
                                                <div class="col-sm-6 profile-user-details clearfix">
                                                    <div class="profile-user-details-label">
                                                        Price
                                                    </div>
                                                    <div class="profile-user-details-value">
                                                        {{$service->price}} MAD
                                                    </div>
                                                </div>



                                            </div>





                                    </div>




                                </div>





                            </div>



                        </div>





                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>



    </div>
</div>
@endsection

@section('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

@endsection
