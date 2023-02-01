<table cellpadding="5" cellspacing="0" border="0" style="padding-left: 50px;margin-left: 2.2%;width: 75%;">
    <thead>
        <tr>
            <th>Code</th>
            <th>Designationt</th>
            <th>Unite</th>
            <th>P.U.H.T</th>
            <th>TVA</th>
            <th>Qte</th>
            <th>Qte livrée</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($details as $d)
        <tr>
                <td>{{ $d->produit_id }}</td>
                <td>{{ $d->produit->designation }}</td>
                <td>{{ $d->produit->uniteReglementaire->code }}</td>
                <td>{{ $d->puht }}</td>
                <td>{{ $d->tva }}</td>
                <td>{{ $d->qte }}</td>
                <td>
                    @php
                    $qte = 0
                    @endphp
                    @foreach ($d->convention->bls as $bl)
                        @foreach ($bl->blDetails as $item)
                        @if ($item->produit_id == $d->produit_id )
                            @php
                                $qte += $item->qte_livree
                            @endphp
                        @endif
                        @endforeach
                    @endforeach
                    {{$qte }}
                </td>
         </tr>
        @endforeach


    </tbody>
</table>
