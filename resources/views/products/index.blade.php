@extends('layouts.app')
@section('styles')
    <style>
.form-control-borderless {
    border: none;
}

.form-control-borderless:hover, .form-control-borderless:active, .form-control-borderless:focus {
    border: none;
    outline: none;
    box-shadow: none;
}
.item-block, .fh5co-services .item-block {
    margin-bottom: 3em;
    float: left;
}
.fh5co-property {
    background: #fff;
    text-align: left;
    width: 100%;
    float: left;
    -webkit-box-shadow: 4px 11px 35px -14px rgb(0 0 0 / 50%);
    -moz-box-shadow: 4px 11px 35px -14px rgba(0, 0, 0, 0.5);
    -ms-box-shadow: 4px 11px 35px -14px rgba(0, 0, 0, 0.5);
    -o-box-shadow: 4px 11px 35px -14px rgba(0, 0, 0, 0.5);
    box-shadow: 4px 11px 35px -14px rgb(0 0 0 / 50%);
}

.fh5co-property figure {
    margin-bottom: 0;
    position: relative;
}


.fh5co-property figure img {
    margin-bottom: 0;
}
.img-responsive {
    display: block;
    width: 100%;
    height: 250px;
}
.fh5co-property figure .tag {
    position: absolute;
    bottom: 0;
    right: 0;
    padding: 2px 10px;
    background: #118DF0;
    color: #fff;
    display: -moz-inline-stack;
    display: inline-block;
    zoom: 1;
    *display: inline;
}
.fh5co-property .fh5co-property-innter {
    padding: 30px 30px 30px 30px;
}

#best-deal .item-block h3, .fh5co-services .item-block h3 {
    font-size: 20px;
}

.fh5co-property .price-status {
    margin-bottom: 20px;
}

.fh5co-property .price-status .price {
    font-size: 28px;
    color: #4CB648;
    margin-bottom: 10px;
    position: relative;
}

 .item-block p:last-child, .fh5co-services .item-block p:last-child {
    margin-bottom: 0;
}

.fh5co-property .fh5co-property-specification {
    border-top: 1px solid #f0f0f0;
    background: #f7f7f7;
    padding: 15px 30px 15px 30px;
    font-size: 13px;
    margin-bottom: 0;
}

.fh5co-property .fh5co-property-specification > span {
    margin-right: 10px;
}
    </style>
@endsection
@section('content')

<div class="container mt-5">



    <div class="row justify-content-center mb-3">
        <div class="col-12 col-md-10 col-lg-8">
            <form class="card card-sm" method="GET" action="{{route('products')}}">
                <div class="card-body row no-gutters align-items-center">
                    <div class="col-auto">
                        <i class="fa fa-search" aria-hidden="true"></i>

                    </div>
                    <!--end of col-->
                    <div class="col">
                        <input class="form-control form-control-lg form-control-borderless"
                        type="search" name="search" placeholder="Search topics or keywords">
                    </div>
                    <!--end of col-->
                    <div class="col-auto">
                        <button class="btn btn-lg btn-primary" type="submit">Search</button>
                    </div>
                    <!--end of col-->
                </div>
            </form>
        </div>
        <!--end of col-->
    </div>







    <div class="main-body">

        <div class="row">


            @foreach ($products as $pro)


            <div class="col-md-4 item-block animate-box" data-animate-effect="fadeIn">

                <div class="fh5co-property">
                    <figure>
                        <img src="{{asset('uploads/products_images/'.$pro->image)}}" alt="Free Website Templates FreeHTML5.co" class="img-responsive">
                        <a href="#" class="tag">For Sale</a>
                    </figure>
                    <div class="fh5co-property-innter">
                        <h3><a href="{{ route('productDetails',$pro->slug)}}">{{$pro->name}}</a></h3>
                        <div class="price-status">
                         <span class="price">{{$pro->sale_price}} MAD</span>
                   </div>
                   <p>
                    {{substr(strip_tags($pro->description),0,100)}}..
                    </p>
                </div>
                <p class="fh5co-property-specification">

                    <span>stock : <strong>

                        @if ($pro->stock)
                            <i class="fa fa-check" style="color: rgb(0, 173, 0)"></i>
                        @else
                            No
                        @endif
                    </strong>
                </span>

                </p>
                </div>

            </div>


            @endforeach




        </div>





    </div>
    <div class="row justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection

@section('scripts')

<script>



</script>
@endsection
