<table cellpadding="5" cellspacing="0" border="0" style="padding-left: 50px;margin-left: 2.2%;width: 75%;">
    <thead>
        <tr>
            <th>N° BL</th>
            <th>Date</th>
            <th>entite</th>
            <th>Etat</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($details as $d)
            <tr>
                <td>{{ $d->no_bl.'/'.$d->annee }}</td>
                <td>{{ \Carbon\Carbon::parse($d->date)->format('d/m/Y') }}</td>
                <td>{{ $d->entite->nom }}</td>
                <td>{{ ($d->sortie == 1 ) ? 'Validée' : 'Préparation' }}</td>
            </tr>
        @endforeach


    </tbody>

</table>
