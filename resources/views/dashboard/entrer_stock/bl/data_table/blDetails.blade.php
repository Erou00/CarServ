<table cellpadding="5" cellspacing="0" border="0" style="padding-left: 50px;margin-left: 2.2%;width: 75%;">
    <thead>
        <tr>

            <th>Code</th>
            <th>Designationt</th>
            <th>Unite</th>
            <th>Qte</th>
            <th>Qte livree </th>
            <th>Magasin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($details as $bld)
            <tr>

                <td>{{ $bld->produit->id }}</td>
                <td>{{ $bld->produit->designation }}</td>
                <td>{{ $bld->produit->uniteReglementaire->code }}</td>
                <td>{{ $bld->qte }}</td>
                <td>{{ $bld->qte_livree }}</td>
                <td>{{ $bld->magasin->nom }}</td>


            </tr>
            <!-- Modal -->
        @endforeach
    </tbody>
</table>
