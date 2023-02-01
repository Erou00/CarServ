
@if ( $sortie == "preparation" || $sortie == "validation" )


<select name="" class="form-control mt-1" id="change-etat{{$id}}" {{  $imp == false ? 'disabled' : ''}}>
    @if ( $sortie == "preparation")
    <option value="preparation" {{ $sortie == "preparation" ? 'selected' : '' }}>Preparée</option>
    @endif

    <option value="validation" {{ $sortie == "validation" ? 'selected' : '' }}>Classée</option>

    @if (auth()->user()->hasPermission('annulation_bs'))
        <option value="annulation" {{ $sortie == "annulation" ? 'selected' : '' }}>Annulée</option>
    @endif

   </select>

@else

<span class="badge bg-danger">Annulée</span>


@endif

   <script>

$(document).ready(function() {
    $("#change-etat{{$id}}").on('change',function() {

        if($(this).val() != '')
                {
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = "{{ csrf_token() }}";

                $.ajax({
                    url:"{{route('bs.classement',$id)}}",
                    method:"POST",
                    data:{select:select, sortie:value, _token:_token, dependent:dependent},
                    success:function(result)
                    {
                        new Noty({
                                    layout: 'topRight',
                                    type: 'alert',
                                    text: result,
                                    killer: true,
                                    timeout: 2000,
                                }).show();
                    }
                })
                }


        table.draw();

    });

})

   </script>

