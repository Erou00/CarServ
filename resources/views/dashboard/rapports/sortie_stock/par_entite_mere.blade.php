<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SORTIE STOCK MULTICRITERE</title>
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



<main style="margin-top: 100px;page-break-after: always;">
            @foreach ($data['entitesMere'] as $entiteMere)


                    <h3 style="margin-left: 100px"><strong>{{ $entiteMere->nom }}</strong></h3>

                    @foreach ($data['entites'] as $entite)
                        @if ( $entite['entite_mere_id'] == $entiteMere->id)
                            <h5 style="margin-left: 100px"><strong>{{ $entite['nom'] }}</strong></h5>

                            @php ($names = 0 )

                            @foreach ($data['details'] as $item)
                                @if ($item['enID'] == $entite['id'])
                                    @php (  $names = $item['enID'])
                                @endif
                            @endforeach


                                @if ( $names != 0 )
                                <table class="table data">
                                    <thead>
                                        <tr>
                                            <th scope="col">Categorie</th>
                                            <th scope="col">Sous Categorie</th>
                                            <th scope="col">Marque/Famille</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Designation</th>
                                            <th scope="col">Qte livree</th>
                                            <th scope="col">No bl</th>
                                            <th scope="col">Date</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach ($data['details'] as $item)

                                             @if ($item['enID'] == $entite['id'])
                                             <tr>
                                                <td>{{$item['categorie']['nom']}}</td>
                                                <td>{{$item['sousCategorie']['nom']}}</td>
                                                <td>{{$item['marque']['nom']}}</td>

                                                <td>{{$item['id']}}</td>
                                                <td>{{$item['designation']}}</td>
                                                <td>{{$item['qte_donnee'] }}</td>
                                                <td>{{ $item['no_bl']}}</td>
                                                <td>{{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}</td>
                                            </tr>
                                             @endif
                                             @endforeach

                                    </tbody>
                                </table>
                                @endif






                        @endif
                    @endforeach




            @endforeach


</main>









</body>
</html>
