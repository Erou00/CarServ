<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Invoice</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css" media="screen" >


            html {
                font-family: sans-serif;
                line-height: 1.15;
                margin: 0;
            }
            .fa-check:before {
            font-family: DejaVu Sans;
            content: "\2611";
            color:darkgreen;
            font-size:1.2rem;
}
.fa-exclamation-triangle:before{
            font-family: DejaVu Sans;
            content: "\26A0";
            color:darkorange;
            font-size:1.2rem;
 }
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 400;
                line-height: 1;
                color: #212529;
                text-align: left;
                background-color: #fff;
                font-size: 10px;
                margin: 4pt;
            }
            h4 {
                margin-top: 0;
                margin-bottom: 0.2rem;
            }
            p {
                margin-top: 0;
                margin-bottom: .2rem;
            }
            strong {
                font-weight: bolder;
            }
            img {
                vertical-align: middle;
                border-style: none;
            }
            table {
                border-collapse: collapse;
            }
            th {
                text-align: inherit;
            }
            h4, .h4 {
                margin-bottom: 0.3rem;
                font-weight: 500;
                line-height: 1;
            }
            h4, .h4 {
                font-size: 1.5rem;
            }
            .table {

                width: 80%;
                margin: 0px 10% 0px 10%;
                margin-bottom: .5rem;
                color: #212529;
            }
            .table th,
            .table td {
                padding: 0.5rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }
            .table thead th {
                vertical-align: bottom;
                border-bottom: 1px solid #dee2e6;
            }
            .table tbody + tbody {
                border-top: 1px solid #dee2e6;
            }
            .mt-5 {
                margin-top: 1rem !important;
            }
            .pr-0,
            .px-0 {
                padding-right: 0 !important;
            }
            .pl-0,
            .px-0 {
                padding-left: 0 !important;
            }
            .text-right {
                text-align: right !important;
            }
            .text-center {
                text-align: center !important;
            }
            .text-uppercase {
                text-transform: uppercase !important;
            }
            * {
                font-family: "DejaVu Sans";
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                line-height: 1;
            }
            .party-header {
                font-size: 1.2rem;
                font-weight: 400;
            }
            .total-amount {
                font-size: 10px;
                font-weight: 700;
            }
            .border-0 {
                border: none !important;
            }
            .signature {
                margin-top: 80px;
                 display: flex;
                justify-content: space-between;
            }

            .signature .s-client{
              margin-bottom: 5px;
              margin-left: 500px;
         }

        </style>
    </head>
<body>




<table class="table mt-1">
    <tbody>
        <tr>
            <td class="border-0 pl-0" width="70%">
                <img src="" alt="logo" height="70" >
            </td>
            <td class="border-0 pl-0 pt-9 mt-9" >
                <p>Date : <strong>{{$data['invoice-date']}}</strong></p>
            </td>
        </tr>
    </tbody>
</table>


      {{-- Seller - Buyer --}}
      <table class="table">
        <thead>
            <tr>
                <th class="border-0 pl-0 party-header" width="48.5%">

                </th>
                <th class="border-0" width="3%"></th>
                <th class="border-0 pl-0 party-header">

                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-0">

                    <p class="buyer-name">
                        <strong>{{$data['name']}}</strong>
                    </p>

                    <p class="buyer-address">
                        Tel : <strong>{{$data['phone']}}</strong>
                    </p>

                    <p class="buyer-address">
                        CIN : <strong>{{$data['cin']}}</strong>
                    </p>

                    <p class="buyer-address">
                        E-mail : <strong>{{$data['email']}}</strong>
                    </p>

                    <p class="buyer-address">
                        Adresse : <strong>{{$data['adress']}}</strong>
                    </p>

                </td>
                <td class="border-0"></td>
                <td class="px-0">


                </td>
            </tr>
        </tbody>
    </table>


     {{-- Table --}}
     <table class="table">
        <thead>
            <tr>
                <th scope="col" class="text-left pl-0">Car</th>


                <th scope="col" class="text-right ">Services</th>

                <th scope="col" class="text-right ">Amount</th>

                <th scope="col" class="text-right ">date</th>



            </tr>
        </thead>
        <tbody>
            {{-- Items --}}

            <tr>


                    <td class="text-left">
                        <strong>{{$data['car']}}</strong>
                    </td>



                    @php
                    $amount = 0
                    @endphp

                    <td class="text-right">
                        @foreach ($data['services'] as $item)
                        @php
                        $amount += $item['price']
                        @endphp
                        <Strong>{{$item['name']}}</Strong><br>
                        @endforeach
                    </td>

                    <td class="text-right pr-0">
                         <strong>{{$amount}} Dh</strong>
                    </td>

                    <td class="text-right pr-0">
                        pour le: <strong>{{$data['date']}}</strong>
                    </td>
            </tr>


        </tbody>
    </table>



     {{-- signature --}}
    <div class="signature">
        <div class="s-client">
            <h3> Signature mechanic</h3>
        </div>

        <div class="s-client">
            <h3>Signature client</h3>
        </div>

    </div>

      {{-- signature --}}




</body>
</html>
