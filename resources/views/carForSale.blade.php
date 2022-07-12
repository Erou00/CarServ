@extends('layouts.app')
@section('styles')
    <style>

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
    height: auto;
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



        <form action="{{route('carForSale')}}" method="GET">

            <div class="row mb-3">
                <div class="col-sm">
                    <select class="form-select form-control dynamic" name="mark_id" id="marque"
                    data-dependent="model" aria-label="Default select example"  >
                    <option option value="" >Choose mark</option>
                    @foreach ($marques as $marque)
                    <option   value="{{$marque->id}}">{{$marque->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="col-sm">
                    <select name="model_id" id="model" class="form-control" data-dependent="model"

                                aria-label="Default select example" style="appearance: auto;">

                    </select>
                </div>
                <div class="col-sm">
                    <input type="number" class="form-control" id="" placeholder="min price" name="minPrice">
                </div>
                 <div class="col-sm">
                     <input type="number" class="form-control" id="" placeholder="max price" name="maxPrice">
                </div>
            </div>

            <div class="row justify-content-center mb-3">

                    <button class="btn-primary" style="width: 200px; height: 50px;">Search</button>


            </div>
        </form>

    <div class="main-body">

        <div class="row">


            @foreach ($cars as $car)
                <div class="col-md-4 item-block">

                <div class="fh5co-property">
                    <figure>


                        <div id="header-carousel{{$car->id}}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach (json_decode($car->images) as $key=>$item)

                                <div class="carousel-item {{($key == 0 ? 'active':'')}}">
                                    <img  src="{{asset('uploads/cars_images/'.$item)}}" alt="Image" class="img-responsive" style="height: 280px">
                                </div>



                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel{{$car->id}}"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel{{$car->id}}"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>


                        {{-- <img src="{{asset('uploads/services_images/4eZoMbnQFTvNrziKTPTLDmpJepPvNHyWYf52ltRY.jpg')}}" alt="Free Website Templates FreeHTML5.co" class="img-responsive"> --}}
                        <a href="#" class="tag">For Sale</a>
                    </figure>
                    <div class="fh5co-property-innter">
                        <h3><a href="{{route('carDetails',$car->slug)}}">{{$car->title}}</a></h3>
                        <div class="price-status">
                         <span class="price">{{$car->price}}MAD</span>
                   </div>
                   <p>{{substr(strip_tags($car->description),0,120)}}..</p>
                </div>
                <p class="fh5co-property-specification">
                    <span><strong>{{$car->fiscal_power}}</strong> Horses</span>
                    <span><strong>{{$car->doors}}</strong> Doors</span>
                    <span>Fist Hand : <strong>

                        @if ($car->first_hand)
                            <i class="fa fa-check" style="color: rgb(0, 173, 0)"></i>
                        @else
                            No
                        @endif
                    </strong></span>


                </p>
                </div>

            </div>
            @endforeach




        </div>



        <div class="row justify-content-center">
            {{ $cars->links() }}
        </div>

    </div>
</div>
@endsection

@section('scripts')

<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
<script>

    $(document).ready(function(){
            $('.dynamic').change(function(){
                console.log('work');
                if($(this).val() != '')
                {
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = "{{ csrf_token() }}";

                console.log(select);
                console.log(value);
                console.log(dependent);
                $.ajax({
                    url:"{{route('getModel')}}",
                    method:"POST",
                    data:{select:select, value:value, _token:_token, dependent:dependent},
                    success:function(result)
                    {
                    $('#'+dependent).html(result);
                    }
                })
                }
                });

                $('#marque').change(function(){
                    $('#model').val('');

                });

    })





</script>
@endsection
