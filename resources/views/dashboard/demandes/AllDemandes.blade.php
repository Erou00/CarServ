@extends('dashboard.layouts.app')


@section('styles')

<link href="{{asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">

<style>
    .box{
    width: 96%;
    height: 380px;
    position: relative;

}
.box  .box-head{
    position: absolute;
    height: 55%;
    width: 100%;
    background-color: white;
    justify-content: space-between;
    display: flex;
    border-radius: 12px  12px 0 0 ;
}

.box  .box-head .info{
    padding: 1rem;
}


.box  .box-head .info p{
    font-size: 20px;

}
.box  .box-head .info p.v-info{
    font-size: 13px;
                        font-weight: bold;
                        /* border: 3px solid #000; */
                        padding: 1px 4px;
                        border-radius: 5px;
                        text-align: center;
                        background-color: #d81324;
                        margin-top: -11px;
                        width: 100%;
                        color: white;
}
.box  .box-head div h2.etat{
   text-align: end
}
.box  .box-head .img-area{
    position: relative;
    width: 140px;
    height: 140px;
    background-color: #d81324;
    border-radius: 50%;
    /* float: right; */
    z-index: 1;
    top: 4px;
    display: flex;
    justify-content: center;
}
.box  .box-head .img-area img{
    /* margin-left: 8px; */
}

.box  .box-head h4{
    background-color: black;
    color: white;
    padding: 1px 10px;

    margin-top: 12px;
    border-radius: 5px;

}
.box  .box-end{
    position: absolute;
    height: 45%;
    width: 100%;
    top: 55%;
    background-color: black;
    border-radius: 0 0 12px 12px;
    justify-content: space-between;
    padding: 8px
}

.box  .box-end .v-footer{
    position: absolute;
    left: 0;
    bottom: 0;
    display: flex;
    width: 100%;
    justify-content: space-between;
}


/* .box  .box-end .v-footer a{

}
.box  .box-end .v-footer div{
    position: absolute;
    right: 0;
} */

</style>

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
            <h4 class="page-title">All Demandes</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card" >
            <div class="card-body">


                <all-demandes :demandes="{{$demandes}}"></all-demandes>




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


     <script src="{{asset('assets/js/vendor/responsive.bootstrap5.min.js')}}"></script>

     <!-- third party js ends -->

     <!-- demo app -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

@endsection
