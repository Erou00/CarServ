<table cellpadding="5" cellspacing="0" border="0" style="padding-left: 50px;margin-left: 2.2%;width: 75%;">
    <thead>
        <tr>
            <th scope="col">Code</th>
            <th scope="col">Designation</th>
            <th scope="col">Unité</th>
            <th scope="col">Qté Demandee</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details->sortBy('created_at') as $details)
            <tr>

                <td>{{ $details->produit->id }}</td>
                <td>{{ $details->produit->designation }}</td>
                <td>{{ $details->produit->uniteReglementaire->code }}</td>
                <td>{{ $details->qte_demandee }}</td>


            </tr>
            <!-- Modal -->
        @endforeach
    </tbody>
</table>
