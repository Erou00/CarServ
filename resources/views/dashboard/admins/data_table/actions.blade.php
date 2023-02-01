
    <a href="{{ route('utilisateurs.edit', $id) }}" class="btn btn-warning btn-sm">
        <i class="fa fa-edit"></i>Editer</a>


    <form action="{{ route('utilisateurs.destroy', $id) }}" class="my-1 my-xl-0" method="post" style="display: inline-block;">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> Supprimer</button>
    </form>

