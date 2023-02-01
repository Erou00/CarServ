<a href="{{ route('categories.edit', $id) }}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> Éditer</a>


<form action="{{ route('categories.destroy', $id) }}" class="my-1 my-xl-0" method="post" style="display: inline-block;">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-default btn-sm delete">
        <i class="fa fa-trash"></i> Supprimer</button>
</form>
