@extends('dashboard.layouts.app')


@section('styles')

<link href="{{asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">

<style>
    h2 {
    clear: both;
    font-size: 1.8em;
    margin-bottom: 10px;
    padding: 10px 0 10px 30px;
}

h3 > span {
    border-bottom: 2px solid #C2C2C2;
    display: inline-block;
    padding: 0 5px 5px;
}

/* USER PROFILE */
#user-profile h2 {
    padding-right: 15px;
}
#user-profile .profile-status {
	font-size: 0.75em;
	padding-left: 12px;
	margin-top: -10px;
	padding-bottom: 10px;
	color: #8dc859;
}
#user-profile .profile-status.offline {
	color: #fe635f;
}
#user-profile .profile-img {
	border: 1px solid #e1e1e1;
	padding: 4px;
	margin-top: 10px;
	margin-bottom: 10px;
}
#user-profile .profile-label {
	text-align: center;
	padding: 5px 0;
}
#user-profile .profile-label .label {
	padding: 5px 15px;
	font-size: 1em;
}
#user-profile .profile-stars {
	color: #FABA03;
	padding: 7px 0;
	text-align: center;
}
#user-profile .profile-stars > i {
	margin-left: -2px;
}
#user-profile .profile-since {
	text-align: center;
	margin-top: -5px;
}
#user-profile .profile-details {
	padding: 15px 0;
	border-top: 1px solid #e1e1e1;
	border-bottom: 1px solid #e1e1e1;
	margin: 15px 0;
}
#user-profile .profile-details ul {
	padding: 0;
	margin-top: 0;
	margin-bottom: 0;
	margin-left: 40px;
}
#user-profile .profile-details ul > li {
	margin: 3px 0;
	line-height: 1.5;
}

#user-profile .profile-details ul > li > span {
	color: #34d1be;
}
#user-profile .profile-header {
	position: relative;
}
#user-profile .profile-header > h3 {
	margin-top: 10px
}
#user-profile .profile-header .edit-profile {
	margin-top: -6px;
	position: absolute;
	right: 0;
	top: 0;
}
#user-profile .profile-tabs {
	margin-top: 30px;
}
#user-profile .profile-user-info {
	padding-bottom: 20px;
}
#user-profile .profile-user-info .profile-user-details {
	position: relative;
	padding: 4px 0;
}
#user-profile .profile-user-info .profile-user-details .profile-user-details-label {
	width: 110px;
	float: left;
	bottom: 0;
	font-weight: bold;
	left: 0;
	position: absolute;
	text-align: right;
	top: 0;
	width: 110px;
	padding-top: 4px;
}
#user-profile .profile-user-info .profile-user-details .profile-user-details-value {
	margin-left: 120px;
}
#user-profile .profile-social li {
	padding: 4px 0;
}
#user-profile .profile-social li > i {
	padding-top: 6px;
}
@media only screen and (max-width: 767px) {
	#user-profile .profile-user-info .profile-user-details .profile-user-details-label {
		float: none;
		position: relative;
		text-align: left;
	}
	#user-profile .profile-user-info .profile-user-details .profile-user-details-value {
		margin-left: 0;
	}
	#user-profile .profile-social {
		margin-top: 20px;
	}
}
@media only screen and (max-width: 420px) {
	#user-profile .profile-header .edit-profile {
		display: block;
		position: relative;
		margin-bottom: 15px;
	}
	#user-profile .profile-message-btn .btn {
		display: block;
	}
}


.main-box {
    background: #FFFFFF;
    -webkit-box-shadow: 1px 1px 2px 0 #CCCCCC;
    -moz-box-shadow: 1px 1px 2px 0 #CCCCCC;
    -o-box-shadow: 1px 1px 2px 0 #CCCCCC;
    -ms-box-shadow: 1px 1px 2px 0 #CCCCCC;
    box-shadow: 1px 1px 2px 0 #CCCCCC;
    margin-bottom: 16px;
    padding: 20px;
}
.main-box h2 {
    margin: 0 0 15px -20px;
    padding: 5px 0 5px 20px;
    border-left: 10px solid #c2c2c2; /*7e8c8d*/
}

.btn {
    border: none;
	padding: 6px 12px;
	border-bottom: 4px solid;
	-webkit-transition: border-color 0.1s ease-in-out 0s, background-color 0.1s ease-in-out 0s;
	transition: border-color 0.1s ease-in-out 0s, background-color 0.1s ease-in-out 0s;
	outline: none;
}
.btn-default,
.wizard-cancel,
.wizard-back {
	background-color: #7e8c8d;
	border-color: #626f70;
	color: #fff;
}
.btn-default:hover,
.btn-default:focus,
.btn-default:active,
.btn-default.active,
.open .dropdown-toggle.btn-default,
.wizard-cancel:hover,
.wizard-cancel:focus,
.wizard-cancel:active,
.wizard-cancel.active,
.wizard-back:hover,
.wizard-back:focus,
.wizard-back:active,
.wizard-back.active {
	background-color: #949e9f;
	border-color: #748182;
	color: #fff;
}
.btn-default .caret {
	border-top-color: #FFFFFF;
}
.btn-info {
	background-color: #5daee7;
	border-color: #4c95c9;
}
.btn-info:hover,
.btn-info:focus,
.btn-info:active,
.btn-info.active,
.open .dropdown-toggle.btn-info {
	background-color: #4c95c9;
	border-color: #3f80af;
}
.btn-link {
	border: none;
}
.btn-primary {
	background-color: #3fcfbb;
	border-color: #2fb2a0;
}
.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary.active,
.open .dropdown-toggle.btn-primary {
	background-color: #38c2af;
	border-color: #2aa493;
}
.btn-success {
	background-color: #8dc859;
	border-color: #77ab49;
}
.btn-success:hover,
.btn-success:focus,
.btn-success:active,
.btn-success.active,
.open .dropdown-toggle.btn-success {
	background-color: #77ab49;
}
.btn-danger {
	background-color: #fe635f;
	border-color: #dd504c;
}
.btn-danger:hover,
.btn-danger:focus,
.btn-danger:active,
.btn-danger.active,
.open .dropdown-toggle.btn-danger {
	background-color: #dd504c;
}
.btn-warning {
	background-color: #f1c40f;
	border-color: #d5ac08;
}
.btn-warning:hover,
.btn-warning:focus,
.btn-warning:active,
.btn-warning.active,
.open .dropdown-toggle.btn-warning {
	background-color: #e0b50a;
	border-color: #bd9804;
}

.icon-box {

}
.icon-box .btn {
	border: 1px solid #e1e1e1;
	margin-left: 3px;
	margin-right: 0;
}
.icon-box .btn:hover {
	background-color: #eee;
	color: #2BB6A3;
}

a {
    color: #2bb6a3;
	outline: none !important;
}
a:hover,
a:focus {
	color: #2bb6a3;
}



textarea.form-control {
    height: auto;
}
.form-control {
    border-radius: 0px;
    border-color: #e1e1e1;
    box-shadow: none;
    -webkit-box-shadow: none;
}
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
                    <li class="breadcrumb-item active">Mechanics</li>
                </ol>
            </div>
            <h4 class="page-title">Mechanics</h4>
        </div>
    </div>
</div>
<!-- end page title -->





<!-- row -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="container ">
                    <div class="row" id="user-profile">
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <div class="main-box clearfix">
                                <h2>{{ ucfirst($mechanic->last_name) .' '. ucfirst($mechanic->first_name )}}</h2>
                                <img src="{{ isset($mechanic->image) ? $mechanic->getImagePathAttribute : asset('uploads/users_images/default.png')}}" width="180" alt="" class="profile-img img-responsive center-block">
                                <div class="profile-label">

                                    <span class="label label-danger">{{ ucfirst($mechanic->roles->first()->name)}}</span>


                                </div>


                                <div class="profile-since">
                                    Membre depuis : {{date('d M Y', strtotime($mechanic->created_at))}}
                                </div>

                                <div class="profile-details">
                                    <ul class="fa-ul">

                                        <li><i class="fa-li fas fa-oil-can"></i>demandes: <span>{{$mechanic->Mdemandes()->count()}}</span></li>
                                        <li><i class="fa-li fas fa-cogs"></i>Affected: <span>{{$mechanic->MAdemandes()->count()}}</span></li>
                                        <li><i class="fa-li fas fa-check-circle"></i>Acheved: <span>{{$mechanic->MACdemandes()->count()}}</span></li>

                                    </ul>
                                </div>
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
                                    <h3><span>mechanic info</span></h3>
                                    <a href="{{route('dashboard.mechanics.edit',$mechanic->id)}}" class="btn btn-primary edit-profile">
                                        <i class="fa fa-pencil-square fa-lg"></i> Edit profile
                                    </a>
                                </div>

                                <div class="row profile-user-info mt-2">
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-6 profile-user-details clearfix">
                                                <div class="profile-user-details-label">
                                                    Nom
                                                 </div>
                                                 <div class="profile-user-details-value">
                                                     {{$mechanic->first_name}}
                                                 </div>
                                            </div>


                                            <div class="col-sm-6 profile-user-details clearfix">
                                                <div class="profile-user-details-label">
                                                    Prénom
                                                </div>
                                                <div class="profile-user-details-value">
                                                    {{$mechanic->last_name}}
                                                </div>
                                            </div>

                                            <div class="col-sm-6 profile-user-details clearfix">
                                                <div class="profile-user-details-label">
                                                    CIN
                                                </div>
                                                <div class="profile-user-details-value">
                                                    {{$mechanic->cin}}
                                                </div>
                                            </div>

                                            <div class="col-sm-6 profile-user-details clearfix">
                                                <div class="profile-user-details-label">
                                                    Email
                                                </div>
                                                <div class="profile-user-details-value">
                                                    {{$mechanic->email}}
                                                </div>
                                            </div>

                                            <div class="col-sm-6 profile-user-details clearfix">
                                                <div class="profile-user-details-label">
                                                    Téléphone
                                                </div>
                                                <div class="profile-user-details-value">
                                                    {{$mechanic->phone_number}}
                                                </div>
                                            </div>

                                            <div class="col-sm-6 profile-user-details clearfix">
                                                <div class="profile-user-details-label">
                                                    Addresse
                                                </div>
                                                <div class="profile-user-details-value">
                                                    {{$mechanic->adress}}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-4 profile-social">
                                        <ul class="fa-ul">
                                            <li><i class="fa-li fa fa-twitter-square"></i><a href="#">@scjohansson</a></li>
                                            <li><i class="fa-li fa fa-linkedin-square"></i><a href="#">John Doe </a></li>
                                            <li><i class="fa-li fa fa-facebook-square"></i><a href="#">John Doe </a></li>
                                            <li><i class="fa-li fa fa-skype"></i><a href="#">Black_widow</a></li>
                                            <li><i class="fa-li fa fa-instagram"></i><a href="#">Avenger_Scarlett</a></li>
                                        </ul>
                                    </div> --}}
                                </div>


                            </div>

                            <div class="main-box clearfix">
                                <h2><span>demande</span></h2>
                                {{-- {{dd($demande)}} --}}
                                <admin-mechanic-demandes :demandes="{{$demandes}}"></admin-mechanic-demandes>
                            </div>


                        </div>





                    </div>



                </div>





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
