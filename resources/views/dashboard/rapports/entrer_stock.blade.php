<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style >



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



                    table {
                        border-collapse: collapse;
                    }
                    th {
                        text-align: inherit;
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
                        vertical-align: top;
                        border: 1px solid #000000;
                        text-align: center;
                    }
                    .table thead th {
                        vertical-align: bottom;
                        border-bottom: 1px solid #000000;
                    }
                    .table tbody + tbody {
                        border-top: 1px solid #000000;
                    }

                    .total-amount {
                        font-size: 10px;
                        font-weight: 700;
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
                .pagenum:before {
                    content: counter(page);
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


    <div style="text-align: center;">
        <h1><Strong>ENTRER STOCK MULTICRITERES</Strong></h1>
    </div>







<main style="page-break-after: always;">
    @if ($data['annee'] == true)
        @php
        $start=  $data['yearStart'];
        $now = $data['yearEnd'];
        @endphp

            @for ($i = $start; $i <= $now; $i++)
                <h1 style="y"><strong>ANNÉE : {{ $i }}</strong></h1>

                @php ($annee = 0 )
                @foreach ($data['details'] as $item)
                    @if (Carbon\Carbon::createFromFormat('d/m/Y', $item['date'])->format('Y') == $i)
                        @php (  $annee = $i)
                    @endif
                @endforeach


                @if ( $annee != 0)

                @foreach ($data['categories'] as $cat)
                <h2>LES ARTICLES DE LA CATEGORIE : {{$cat['nom']}}</h2>

                @php ($categories = 0 )
                @foreach ($data['souscategories'] as $scat)
                    @if ($scat['categorie_id'] == $cat['id'])
                           <h3>SOUS CATEGORIE : {{$scat['nom']}}</h3>

                           @php ($sous_categories = 0 )


                           @foreach ($data['details'] as $item)
                               @if ($item['categorie_id'] == $cat['id'] && $item['sous_categorie_id'] == $scat['id'] )
                                   @php (  $categories = $item['categorie_id'])
                                   @php (  $sous_categories = $item['sous_categorie_id'])
                               @endif
                           @endforeach




                           @if ($categories != 0 && $sous_categories != 0)
                           <table class="table data">
                               <thead>
                                   <tr>
                                       <tr>
                                           <th scope="col">Marque/Famille</th>
                                           <th scope="col">Code</th>
                                           <th scope="col">Designation</th>
                                           <th scope="col">Qte livree</th>
                                           <th scope="col">No bl</th>
                                           <th scope="col">Date</th>
                                       </tr>

                               </thead>
                               <tbody>
                                   {{-- Items --}}

                                   @foreach ($data['details'] as $item)
                                      @if ($item['categorie_id'] == $cat['id'] && $item['sous_categorie_id'] == $scat['id'])
                                        @if (Carbon\Carbon::createFromFormat('d/m/Y', $item['date'])->format('Y') == $i)
                                                <tr>
                                                    <td>{{$item['marque']['nom']}}</td>
                                                    <td>{{$item['id']}}</td>
                                                    <td>{{$item['designation']}}</td>
                                                    <td>{{$item['qte_livree'] }}</td>
                                                    <td>{{ $item['no_bl']}}</td>
                                                    <td>
                                                        {{-- {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }} --}}

                                                        {{ Carbon\Carbon::createFromFormat('d/m/Y', $item['date'])->format('d/m/Y') }}

                                                    </td>
                                                </tr>
                                        @endif
                                      @endif

                                   @endforeach


                               </tbody>
                           </table>
                   @endif




                    @endif
                @endforeach


            @endforeach


                @endif

            @endfor
     @else


     @foreach ($data['categories'] as $cat)
         <h2>LES ARTICLES DE LA CATEGORIE : {{$cat['nom']}}</h2>

         @php ($categories = 0 )
         @foreach ($data['souscategories'] as $scat)
             @if ($scat['categorie_id'] == $cat['id'])
                    <h3>SOUS CATEGORIE : {{$scat['nom']}}</h3>

                    @php ($sous_categories = 0 )


                    @foreach ($data['details'] as $item)
                        @if ($item['categorie_id'] == $cat['id'] && $item['sous_categorie_id'] == $scat['id'] )
                            @php (  $categories = $item['categorie_id'])
                            @php (  $sous_categories = $item['sous_categorie_id'])
                        @endif
                    @endforeach




                    @if ($categories != 0 && $sous_categories != 0)
                    <table class="table data">
                        <thead>
                            <tr>
                                <tr>
                                    <th scope="col">Marque/Famille</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">Qte livree</th>
                                    <th scope="col">No bl</th>
                                    <th scope="col">Date</th>
                                </tr>

                        </thead>
                        <tbody>
                            {{-- Items --}}

                            @foreach ($data['details'] as $item)
                               @if ($item['categorie_id'] == $cat['id'] && $item['sous_categorie_id'] == $scat['id'])
                                   <tr>
                                    <td>{{$item['marque']['nom']}}</td>
                                    <td>{{$item['id']}}</td>
                                    <td>{{$item['designation']}}</td>
                                    <td>{{$item['qte_livree'] }}</td>
                                    <td>{{ $item['no_bl']}}</td>
                                    <td>
                                        {{-- {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }} --}}
                                        {{ Carbon\Carbon::createFromFormat('d/m/Y', $item['date'])->format('d/m/Y') }}

                                    </td>
                                </tr>
                               @endif

                            @endforeach


                        </tbody>
                    </table>
            @endif




             @endif
         @endforeach


     @endforeach


     @endif
</main>









</body>
</html>
