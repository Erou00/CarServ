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
                    <li class="breadcrumb-item active">Servicess</li>
                </ol>
            </div>
            <h4 class="page-title">Services</h4>
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
                        <a href="javascript:void(0);" class="btn btn-danger mb-2"
                        data-bs-toggle="modal" data-bs-target="#addservice-modal"
                        >
                            <i class="mdi mdi-plus-circle me-2"></i> Add Services</a>
                    </div>
                    <div class="col-sm-8">

                    </div><!-- end col-->
                </div>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
               @endif

                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Home service</th>
                            <th>Action</th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($services as $service)

                        <tr>

                            <td>
                                <img src="{{ $service->image_path}}" alt="{{$service->name}}" title="contact-img" class="rounded me-3" height="48" width="68">

                            </td>
                        <td>
                           {{$service->name}}
                        </td>

                        <td>
                            @if (($service->home_service))
                            <h3><span class="badge bg-success ">Yes</span></h3>
                            @else
                            <h3><span class='badge bg-danger'>No</span></h3>
                            @endif

                        </td>
                        <td>
                            <a href="javascript:void(0);" class="action-icon"
                            data-bs-toggle="modal" data-bs-target="#updateservice-modal{{$service->id}}"> <i class="mdi mdi-square-edit-outline"></i></a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST"
                                style="display: inline-block">
                               @csrf
                               @method('DELETE')
                              <button type="submit" class="action-icon" style="border: none;background: none"><i class="mdi mdi-delete"></i></button>
                            </form>
                        </td>


                        </tr>

                        <div id="updateservice-modal{{$service->id}}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <div class="text-center mt-2 mb-4">
                                            <a href="index.html" class="text-success">
                                                <span><img src="assets/images/logo-dark.png" alt="" height="18"></span>
                                            </a>
                                        </div>

                                        <form class="ps-3 pe-3" action="{{route('services.update',$service->id)}}" method="POST" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                                        data-upload-preview-template="#uploadPreviewTemplate" enctype="multipart/form-data">

                                        @csrf
                                        @method('PATCH')
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name :</label>
                                                <input class="form-control" type="text" id="name" name="name" placeholder="Diagnostic Test" value="{{$service->name}}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="image" class="form-label">Image :</label>
                                                <input class="form-control" type="file" id="image" name="image" >
                                            </div>

                                            <div class="mb-3">
                                                <img src="{{ $service->image_path}}" alt="{{$service->name}}" title="contact-img" class="rounded me-3" height="180" width="460">


                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-4">
                                                    <label for="name" class="form-label">Home service :</label>
                                                </div>
                                                <div class="col-8 m-0">
                                                    <div class="form-check">
                                                        <!-- Success Switch-->
                                                        <input type="checkbox" id="switch{{$service->id}}" name="home_service" {{($service->home_service)? 'checked':''}}  data-switch="success"/>
                                                        <label for="switch{{$service->id}}" data-on-label="Yes" data-off-label="No"></label>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="my-3 text-center">
                                                <button class="btn btn-danger" type="submit">Edit Service</button>
                                            </div>

                                        </form>


                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->


                        @endforeach



                    </tbody>
                </table>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->


<div id="addservice-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <a href="index.html" class="text-success">
                        <span><img src="assets/images/logo-dark.png" alt="" height="18"></span>
                    </a>
                </div>

                <form class="ps-3 pe-3" action="{{route('services.store')}}" method="POST" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone" data-previews-container="#file-previews"
                data-upload-preview-template="#uploadPreviewTemplate" enctype="multipart/form-data">

                @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name :</label>
                        <input class="form-control" type="text" id="name" name="name" required="" placeholder="Diagnostic Test">
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Image :</label>
                        <input class="form-control" type="file" id="image" name="image" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="name" class="form-label">Home service :</label>
                        </div>
                        <div class="col-8 m-0">
                            <div class="form-check">
                                <!-- Success Switch-->
                                <input type="checkbox" id="switch" name="home_service" checked data-switch="success"/>
                                <label for="switch" data-on-label="Yes" data-off-label="No"></label>
                            </div>
                        </div>

                    </div>


                    <div class="my-3 text-center">
                        <button class="btn btn-danger" type="submit">Add Service</button>
                    </div>

                </form>


            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
