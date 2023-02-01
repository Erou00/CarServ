<a href="{{ route('entites.edit', $id) }}" class="btn btn-sm p-0 m-0"><i class="fa fa-edit text-dark"></i></a>


<form action="{{ route('entites.destroy', $id) }}" class="my-1 my-xl-0" method="post" style="display: inline-block;">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-sm delete p-0 m-0"><i class="fa fa-trash text-dark"></i></button>
</form>
