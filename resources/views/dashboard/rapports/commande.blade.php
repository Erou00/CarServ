<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$data['name']}}</title>
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
                /* border-top: 1px solid #dee2e6; */
            }
            .data th,.data td {
                padding: 0.5rem;
                border: 1.5px solid #000000;
                text-align: center;
                vertical-align: middle;
            }
            .table thead th {
                vertical-align: middle;
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

         .paragraph{
            margin-top: 50px;
            margin-left: 50px;
         }

         .bs_details{
            padding: 3px;
         }
         .duplicata{
            font-size: 26px;
            transform: rotate(341deg);
            margin-bottom: 10px;
         }

        </style>


    </head>
<body>




    <table class="table mt-1">
        <tbody>
            <tr>
                <td class="border-0 pl-0" width="70%">
                    <img src="http://localhost/geststock/assets/images/rym.png" alt="logo" height="150" >
                </td>
                <td class="border-0 pl-0 pt-9 mt-9" >
                    <img src="http://localhost/geststock/assets/images/tgr.png" alt="logo" height="150" >
                </td>
            </tr>
        </tbody>
    </table>


    <table class="table mt-1">
        <tbody>
            <tr>
                <td class="border-0 pl-0" width="70%">
                </td>
                <td class="border-0 pl-0 pt-9 mt-9" >
                    <p>Rabat le, <strong>{{$data['invoice-date']}}</strong></p>
                </td>
            </tr>
        </tbody>
    </table>


      {{-- Seller - Buyer --}}
      <table class="table">
        <thead>
            <tr>
                <th class="border-0 pl-0 party-header" width="60.5%">

                </th>
                <th class="border-0" width="3%"></th>
                <th class="border-0 pl-0 party-header">

                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-0">

                    <p class="buybs_details">
                       Bon Commande N°: <strong>{{$data['name']}}</strong>
                    </p>

                    <p class="buybs_details">
                        Date Commande : <strong>{{$data['date_commande']}}</strong>
                    </p>






                </td>
                <td class="border-0">

                    <p class="buybs_details">
                        Fournisseur : <strong>{{$data['fournisseur']}}</strong>
                    </p>
                    <p class="buybs_details">
                        {!! $data['traitePar'] !!}
                      </p>

                </td>
                <td class="px-0">


                </td>
            </tr>
        </tbody>
    </table>


     {{-- Table --}}
     <table class="table data">
        <thead>
            <tr>
                <th scope="col" class="text-left pl-0">Code</th>
                <th scope="col" class="">DESIGNATION</th>
                <th scope="col" class="">U</th>
                <th scope="col" class="">Qte</th>
                <th scope="col" class="">P.U</th>
                <th scope="col" class="">TVA %</th>
                <th scope="col" class="">MONTANT T.T.C</th>




            </tr>
        </thead>
        <tbody>
            {{-- Items --}}

            @foreach ($data['details']['commandeDetails'] as $item)
                <tr>
                    <td>{{$item['produit_id']}}</td>

                    <td>{{$item['produit']['designation']}}</td>
                    <td>{{$item['produit']['uniteReglementaire']['code']}}</td>
                   <td>{{$item['qte']}}</td>
                   <td>{{$item['puht']}}</td>
                   <td>{{$item['tva']}}</td>
                   <td>{{$item['prix_total']}}</td>
                </tr>
            @endforeach


        </tbody>
    </table>


     {{-- signature --}}


      {{-- signature --}}
      <div class="paragraph" style="margin-left: 500px">
        @php
        $amount = 0
        @endphp

        @foreach ($data['details']['commandeDetails'] as $item)
        @php
        $amount += $item['prix_total']
        @endphp
        @endforeach

        MONTANT  T.T.C <strong>{{' '.number_format($amount,2)}} Dh</strong>
    </div>



</body>
</html>
