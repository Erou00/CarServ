<div class="d-flex">

    <a href="{{ route('produits.edit', $id) }}" class="btn  btn-sm"><i class="fa fa-edit" style="font-weight: bold; color: rgb(0, 0, 0)"></i><span style="color: rgb(209, 145, 49)"></span></a>



    <form action="{{ route('produits.destroy', $id) }}" class=""
     method="post" style="display: inline-block;">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm delete">
            <i class="fa fa-trash" style="font-weight: bold; color: rgb(0, 0, 0)"></i><span style="color: rgb(209, 145, 49)">
            </span></button>
    </form>


    <form action="{{ route('produits.active', $id) }}" class="my-1 my-xl-0" method="post" style="display: inline-block;">
        @csrf
        <input type="hidden"  name="active" value="{{ ($active) ? 0 : 1 }}">
        @method('PUT')
        <button type="submit" class="btn m-0 p-0">
            <span class="badge badge-sm {{($active) ? 'bg-info' : 'bg-danger' }}   mt-2">{{ ($active) ? 'OUI' : 'NON' }}</span>
        </button>
    </form>





</div>
