<div class="d-flex">
    <a href="{{ route('bs.show', $id) }}" class="btn  btn-sm"><i class="fa fa-edit" style="font-weight: bold; color: rgb(0, 0, 0)"></i><span style="color: rgb(209, 145, 49)"></span></a>
   @php
     $print = ($imp == 1) ? 'duplicata': 'non'
   @endphp
    <a href="{{ route('bs.rapport', [$id,'fun',$print]) }}" class="btn btn-sm"><i class="fa fa-print" style="font-weight: bold; color: black;"></i><span style="color: rgb(209, 145, 49)"></span></a>


    <form action="{{ route('bs.destroy', $id) }}" class=""
     method="post" style="">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm delete">
            <i class="fa fa-trash" style="font-weight: bold; color: black;"></i><span style="color: rgb(209, 145, 49)"></span></button>
    </form>

    </div>
