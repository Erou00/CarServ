<table cellpadding="5" cellspacing="0" border="0" style="padding-left: 50px;margin-left: 2.2%;width: 75%;">
    <thead>
        <tr>
            <th>Code</th>
            <th>Designationt</th>
            <th>Unite</th>
            <th>Qte Inventorié</th>
            <th>Ancien Qte</th>
            <th><strong>-/+</strong></th>

        </tr>
    </thead>

    <tbody>

        @foreach ($details as $d)
        <tr>
                <td>{{ $d->produit_id }}</td>
                <td>{{ $d->produit->designation }}</td>
                <td>{{ $d->produit->uniteReglementaire->code }}</td>
                <td>{{ $d->qte_inventorie  }}</td>
                @php
                    $qld_qte = 0
                @endphp
                <td>
                    @if ($d->inventaire->etat == 'validation')
                    {{ ($d->produit->stock) ? $qld_qte = $d->produit->stock->old_qte : $qld_qte = 0 }}
                    @endif
                </td>
                <td>
                    @if ($d->inventaire->etat == 'validation')
                    {{ $d->qte_inventorie -  $qld_qte  }}
                    @endif
                </td>
         </tr>
        @endforeach


    </tbody>
</table>
