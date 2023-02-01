@extends('layouts.dashboard.app')



@section('content')

<section class="content">
  <div>
        <h2>Devises</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a href="">Acceuil</a></li>
        <li class="breadcrumb-item"><a href="">Paramétrage</a></li>
        <li class="breadcrumb-item"><a  href="">Devises</a> </li>
        <li class="breadcrumb-item">Editer</li>
    </ul>


    <div class="box box-primary">

        <div class="box-header">
            {{-- <h3 class="box-title">@lang('site.add')</h3> --}}
        </div><!-- end of box header -->
        <div class="box-body">
            @include('dashboard.partials._errors')

            <form action="{{ route('devises.update', $devise->id) }}" method="post">

                {{ csrf_field() }}
                {{ method_field('put') }}


                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="code" class="form-control" value="{{ $devise->code }}">
                    </div>

                    <div class="form-group">
                        <label>Code :</label>
                        <input type="text" name="designation" class="form-control" value="{{ $devise->designation }}">
                    </div>


                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                </div>
            </form>

        </div><!-- end of box body -->

    </div><!-- end of box -->
</section>
@endsection

@push('scripts')


<script>


</script>

@endpush

