@extends('layouts.app')
@section('styles')
 <style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

        .box { background-color: #fff; border-radius: 8px; border: 2px solid #e9ebef; padding: 50px; margin-bottom: 40px; }
        .box-title { margin-bottom: 30px; text-transform: uppercase; font-size: 16px; font-weight: 700; color: #094bde; letter-spacing: 2px; }
        .plan-selection { border-bottom: 2px solid #e9ebef; padding-bottom: 25px; margin-bottom: 35px; }
        div.plan-selection:last-of-type { border-bottom: 0px; margin-bottom: 0px; padding-bottom: 0px;}
        .plan-data { position: relative; }
        .plan-data label { font-size: 20px; margin-bottom: 15px; font-weight: 400; }
        .plan-text { padding-left: 35px; }
        .plan-price { position: absolute; right: 0px; color: #094bde; font-size: 20px; font-weight: 700; letter-spacing: -1px; line-height: 1.5; bottom: 43px; }
        .term-price { bottom: 18px; }
        .secure-price { bottom: 68px; }
        .summary-block { border-bottom: 2px solid #d7d9de; }
        .summary-block:last-child { border-bottom: 0px; }
        .summary-content { padding: 28px 0px; }
        .summary-price { color: #094bde; font-size: 20px; font-weight: 400; letter-spacing: -1px; margin-bottom: 0px; display: inline-block; float: right; }
        .summary-small-text { font-weight: 700; font-size: 12px; color: #8f929a; }
        .summary-text { margin-bottom: -10px; }
        .summary-title { font-weight: 700; font-size: 14px; color: #1c1e22; }
        .summary-head { display: inline-block; width: 120px; }

        .widget { margin-bottom: 30px; background-color: #e9ebef; padding: 50px; border-radius: 6px; }
        .widget:last-child { border-bottom: 0px; }
        .widget-title { color: #094bde; font-size: 16px; font-weight: 700; text-transform: uppercase; margin-bottom: 25px; letter-spacing: 1px; display: table; line-height: 1; }


/*
@media only screen and (max-width: 777px){
    .container{
        overflow-x: hidden;
    }
}*/
 </style>
 @endsection

 @section('content')
 <div class="container mt-5">
    <div class="row">
    <checkout />
    </div>
 </div>
 @endsection
