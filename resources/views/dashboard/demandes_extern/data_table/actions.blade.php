<div class="d-flex">


        <a href="{{ route('demandes-externe.show', $id) }}" class="btn  btn-sm"><i class="fa fa-edit" style="font-weight: bold; color: rgb(0, 0, 0)"></i><span style="color: rgb(209, 145, 49)"></span></a>


    <a href="{{ route('demandes.rapport', $id) }}" target="_blank" class="btn btn-sm"><i class="fa fa-print" style="font-weight: bold; color: black;"></i><span style="color: rgb(209, 145, 49)"></span></a>



    <form action="{{ route('demandes-externe.show', $id) }}" class=""
     method="post" style="">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-sm delete">
            <i class="fa fa-trash" style="font-weight: bold; color: black;"></i><span style="color: rgb(209, 145, 49)"></span></button>
    </form>


    @php
        $roles = [];
    @endphp

    @foreach (Auth::user()->roles as $item)
         @php
            array_push($roles,$item['name'])
         @endphp
    @endforeach

        @if (in_array('extern',$roles))

        @endif






    </div>
