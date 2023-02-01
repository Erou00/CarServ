<div class="d-flex">
<a href="{{ route('commandes.show', $id) }}" class="btn  btn-sm"><i class="fa fa-edit" style="font-weight: bold; color: rgb(0, 0, 0)"></i><span style="color: rgb(209, 145, 49)"></span></a>
<a href="{{ route('commandes.rapport', $id) }}" target="_blank" class="btn btn-sm"><i class="fa fa-print" style="font-weight: bold; color: black;"></i><span style="color: rgb(209, 145, 49)"></span></a>


<form action="{{ route('commandes.destroy', $id) }}" class=""
 method="post" style="">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-sm delete">
        <i class="fa fa-trash" style="font-weight: bold; color: black;"></i><span style="color: rgb(209, 145, 49)"></span></button>
</form>

</div>
