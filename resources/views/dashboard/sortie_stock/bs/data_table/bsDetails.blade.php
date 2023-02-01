<table cellpadding="5" cellspacing="0" border="0" style="padding-left: 50px;margin-left: 2.2%;width: 75%;">
    <thead>
        <tr>

            <th>Code</th>
            <th>Designationt</th>
            <th>Unite</th>
            <th>Qte Stock</th>
            <th>Q.C </th>
            <th>Q.A </th>
            <th>Magasin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $bld)

            <tr>

                <td>{{ $bld->produit->id }}</td>
                <td>{{ $bld->produit->designation }}</td>
                <td>{{ $bld->produit->uniteReglementaire->code }}</td>
                <td>
                    @php
                    $qte_stock = 0
                     @endphp
                    @foreach ($bld->produit->stocks as $s )
                        @if ($bld->magasin_id  == $s->magasin_id)
                            @php
                                $qte_stock = $s->qte
                            @endphp

                          {{  $qte_stock }}

                        @endif
                    @endforeach
                </td>
                <td>{{ $bld->qte_demandee }}</td>
                <td>{{ $bld->qte_donnee }}</td>
                <td>{{ $bld->magasin->nom }}</td>
            </tr>
            <!-- Modal -->
        @endforeach
    </tbody>
</table>
