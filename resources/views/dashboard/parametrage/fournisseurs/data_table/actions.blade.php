<div class="d-flex">
    <a href="{{ route('fournisseurs.edit', $id) }}" class="btn  btn-sm">
        <i class="fa fa-edit text-dark"></i></a>


    <form action="{{ route('fournisseurs.destroy', $id) }}" class="my-1 my-xl-0" method="post" style="display: inline-block;">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm delete"><i class="fa fa-trash text-dark"></i></button>
    </form>
</div>
