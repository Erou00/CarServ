@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">

    <style>


    </style>
@endsection
@section('content')
<div class="container mt-5">
    <div class="main-body">
        <div class="row">
            <table id="myTable" class="table table-striped" >
                <thead>
                    <tr class="filters">

                        <th></th>
                        <th>Mark</th>
                        <th>Model</th>
                        <th>Address</th>
                        <th>Date</th>


                        <th>State</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($demandes as $demande)
                        <tr>

                            <td><img src="{{asset('uploads/cars_logo/'.$demande->car->marque->logo)}}" alt="{{$demande->car->marque->name}}" srcset="" width="80" height="50">
                            </td>
                            <td>{{$demande->car->marque->name}}</td>
                            <td>{{$demande->car->model->model}}</td>
                            <td>{{$demande->user->adress}}</td>
                            <td>{{date('d/m/Y', strtotime($demande->date))}}</td>

                            <td>{{ $demande->etat}}</td>
                           <td></td>
                            <td><form action="" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger m-0 py-0" style="font-size: 16px"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </form>

                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function(){
            $('#myTable').dataTable();
        });
        </script>
@endsection
