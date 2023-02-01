@if ($facture)
    <table cellpadding="5" cellspacing="0" class="mb-2" border="0" style="padding-left: 50px;margin-left: 2.2%;width: 75%;">
        <thead>
            <tr>
                <th>No Facutre</th>
                <th>No pv</th>
                <th>Montant</th>
                <th>Date depot</th>
                <th>No registre</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $facture->n_facture }}</td>
                <td>{{ $facture->n_pv }}</td>
                <td>{{ $facture->montant }}</td>
                <td>{{ $facture->date_depot }}</td>
                <td>{{ $facture->n_registre }}</td>
                <td class="d-flex">
                    <a href="{{ route('factures.edit', $facture->id) }}" class="btn  btn-sm">
                        <i class="fa fa-edit text-dark"></i></a>

                    <form action="{{route('factures.destroy',$facture->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm delete" ><i class="fa fa-trash text-dark"></i></button>
                      </form>

                </td>
            </tr>
        </tbody>
    </table>
@endif




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
