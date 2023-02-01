@extends('layouts.dashboard.app')

@section('content')

<section class="content">




            <form method="post" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('put')
                @include('dashboard.partials._errors')

                <fieldset class="form-group p-3 mt-2">
                    <legend class="p-2">Profile</legend>

                   {{--name--}}
                   <div class="form-group">
                    <label>Nom <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                </div>

                <h5 class="my-3"><strong>Permissions</strong><span class="text-danger">*</span></h5>

                @php
                    $models = ['stocks','users','roles','type_entites','entites','magasins','categories','sous_categories','marques',
                                    'devises','unite_reglementaires','groupes','produits',
                                    'pays','villes','fournisseurs', 'commandes','conventions',
                                    'marches','bl'];
                @endphp

                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Permissions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($models as $model)
                        <tr>
                            <td><strong>{{ucfirst($model)}}</strong></td>
                            <td>

                                @php
                                    $permissionMaps = ['create', 'read', 'update','delete'];
                                    $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer'];
                                @endphp

                                @foreach ($permissionMaps as $index => $permissionMap)
                                    <div class="animated-checkbox mx-2" style="display:inline-block;">
                                        <label class="m-0">
                                            <input type="checkbox" value="{{ $permissionMap . '_' . $model }}" name="permissions[]" {{ $role->hasPermission( $permissionMap . '_' . $model) ? 'checked' : '' }} class="role">
                                            <span class="label-text">{{$permissionMapsFr[$index]}}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach



                    <tr>
                        <td><strong>Demandes</strong></td>
                        <td>

                            @php
                                $permissionMaps = ['create', 'read', 'update','delete'];
                                $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer'];
                            @endphp

                            @foreach ($permissionMaps as $index => $permissionMap)
                                <div class="animated-checkbox mx-2" style="display:inline-block;">
                                    <label class="m-0">
                                        <input type="checkbox" value="{{ $permissionMap . '_demandes' }}" name="permissions[]" {{ $role->hasPermission( $permissionMap . '_demandes') ? 'checked' : '' }} class="role">
                                        <span class="label-text">{{$permissionMapsFr[$index]}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Demandes Externe</strong></td>
                        <td>

                            @php
                                $permissionMaps = ['create', 'read', 'update','delete'];
                                $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer'];
                            @endphp

                            @foreach ($permissionMaps as $index => $permissionMap)
                                <div class="animated-checkbox mx-2" style="display:inline-block;">
                                    <label class="m-0">
                                        <input type="checkbox" value="{{ $permissionMap . '_demandes_extern' }}" name="permissions[]" {{ $role->hasPermission( $permissionMap . '_demandes_extern') ? 'checked' : '' }} class="role">
                                        <span class="label-text">{{$permissionMapsFr[$index]}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Bs</strong></td>
                        <td>

                            @php
                                $permissionMaps = ['create', 'read', 'update','delete','annulation'];
                                $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer','annuler'];
                            @endphp

                            @foreach ($permissionMaps as $index => $permissionMap)
                                <div class="animated-checkbox mx-2" style="display:inline-block;">
                                    <label class="m-0">
                                        <input type="checkbox" value="{{ $permissionMap . '_bs' }}" name="permissions[]" {{ $role->hasPermission( $permissionMap . '_bs') ? 'checked' : '' }} class="role">
                                        <span class="label-text">{{$permissionMapsFr[$index]}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td><strong>Inventaires</strong></td>
                        <td>

                            @php
                                $permissionMaps = ['create', 'read', 'update','delete','preparation','verification','validation'];
                                $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer','preparation','verification','validation'];
                            @endphp

                            @foreach ($permissionMaps as $index => $permissionMap)
                                <div class="animated-checkbox mx-2" style="display:inline-block;">
                                    <label class="m-0">
                                        <input type="checkbox" value="{{ $permissionMap . '_inventaires' }}" name="permissions[]" {{ $role->hasPermission( $permissionMap . '_inventaires') ? 'checked' : '' }} class="role">
                                        <span class="label-text">{{$permissionMapsFr[$index]}}</span>
                                    </label>
                                </div>
                            @endforeach
                        </td>
                    </tr>

                    </tbody>
                </table><!-- end of table -->

                <div class="form-group">
                    <button type="submit" class="btn btn-default"><i class="fa fa-edit"></i> Enregistrer</button>
                </div>


                </fieldset>
              </form><!-- end of form -->




  </section>

@endsection

