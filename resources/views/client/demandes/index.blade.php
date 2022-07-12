@extends('layouts.app')
@section('styles')
<script src="https://kit.fontawesome.com/39e20eb3b4.js" crossorigin="anonymous"></script>
<style>

.box{
    width: 96%;
    min-height: 340px;
    height: 96%;
    position: relative;

}
.box  .box-head{
    position: absolute;
    height: 55%;
    width: 100%;
    background-color: #f7f0f1;
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
                        background-color: #D81324;
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
    background-color: #D81324;
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

.box  .box-head h5{
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
</style>
@endsection
@section('content')
<div class="container mt-5 car" >
    <div class="main-body">
        <div class="row">

            <div class="">
                <a href="{{route('demandes.createtDemandes')}}" class="btn btn-primary py-3 px-5 mb-4" style="float: right;"><i class="fa fa-plus ms-3 mx-2"></i>New Demande</a>
            </div>



            <all-client-demandes :demandes="{{$demandes}}"></all-client-demandes>





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
