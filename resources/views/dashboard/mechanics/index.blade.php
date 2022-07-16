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
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="{{route('dashboard.mechanics.create')}}" class="btn btn-danger mb-2">
                            <i class="mdi mdi-plus-circle me-2"></i> Add Mechanic</a>
                    </div>
                    <div class="col-sm-8">

                    </div><!-- end col-->
                </div>


                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>Name</th>
                            <th>Cin</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Demandes</th>
                            <th>action</th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($mecaniciens as $index=>$mecanicien)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <img
                                 src="{{ isset($mecanicien->image) ? asset('uploads/users_images/'.$mecanicien->image) : asset('uploads/users_images/default.png')}}"
                                class="" alt="{{$mecanicien->first_name.' '.$mecanicien->last_name}}"
                                width="100px"  height="80px" />
                            </td>
                            <td>{{$mecanicien->first_name.' '.$mecanicien->last_name}}</td>
                            <td>{{$mecanicien->cin}}</td>
                            <td>{{$mecanicien->email}}</td>
                            <td>{{$mecanicien->adress}}</td>
                            <td>{{$mecanicien->phone_number}}</td>
                            <td style="text-align: center;"><a href="{{route('dashboard.mechanics.show',$mecanicien->id)}}}}">{{$mecanicien->MAdemandes()->count()}}</a></td>

                            <td>

                                <a href="{{route('dashboard.mechanics.edit',$mecanicien->id)}}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>

                                <a href="{{route('dashboard.mechanics.show',$mecanicien->id)}}" class="action-icon"> <i class="mdi mdi-eye"></i></a>
                                <form action="{{ route('dashboard.mechanics.destroy', $mecanicien->id) }}" method="POST"
                                     style="display: inline-block" onsubmit="return confirm('Are You Sure?')">
                                    @csrf
                                    @method('DELETE')
                                   <button type="submit" class="action-icon" style="border: none;background: none"><i class="mdi mdi-delete"></i></button>
                                 </form>
                            </td>

                        </tr>





                        @endforeach



                    </tbody>
                </table>



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
