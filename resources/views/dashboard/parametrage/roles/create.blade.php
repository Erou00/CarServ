@extends('layouts.dashboard.app')

@section('content')
    <section class="content">


                <form method="post" action="{{ route('roles.store') }}">
                    @csrf
                    @method('post')

                    @include('dashboard.partials._errors')

                    <fieldset class="form-group p-3 mt-2">
                        <legend class="p-2">Profile</legend>
                    {{-- name --}}
                    <div class="form-group">
                        <label>Nom <span class="text-danger">*</span></label>
                        <input type="text" name="name" autofocus class="form-control" value="{{ old('name') }}"
                            required>
                    </div>

                    <h5 class="my-3"><strong>Permissions</strong><span class="text-danger">*</span></h5>

                    @php
                        $models = ['stocks','users','type_entites','entites','magasins', 'roles', 'categories', 'sous_categories', 'marques',
                        'devises', 'unite_reglementaires', 'groupes', 'produits', 'pays', 'villes', 'fournisseurs',
                         'commandes', 'conventions', 'marches', 'bl'];
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
                                    <td><strong>{{ ucfirst($model) }}</strong></td>
                                    <td>
                                        <div class="animated-checkbox mx-2" style="display:inline-block;">
                                            <label class="m-0">
                                                <input type="checkbox" value="" name="" class="all-roles">
                                                <span class="label-text">Tous</span>
                                            </label>
                                        </div>

                                        @php
                                            //create_roles, read_roles, update_roles, delete_roles
                                            $permissionMaps = ['create', 'read', 'update','delete'];
                                            $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer'];
                                        @endphp

                                        @foreach ($permissionMaps as $index => $permissionMap)

                                            <div class="animated-checkbox mx-2" style="display:inline-block;">
                                                <label class="m-0">
                                                    <input type="checkbox" value="{{ $permissionMap . '_' . $model }}"
                                                        name="permissions[]" class="role">
                                                    <span class="label-text">{{ $permissionMapsFr[$index] }}</span>
                                                </label>
                                            </div>

                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td><strong>Demandes</strong></td>
                                <td>

                                    <div class="animated-checkbox mx-2" style="display:inline-block;">
                                        <label class="m-0">
                                            <input type="checkbox" value="" name="" class="all-roles">
                                            <span class="label-text">Tous</span>
                                        </label>
                                    </div>

                                    @php
                                            //create_roles, read_roles, update_roles, delete_rolesextern
                                            $permissionMaps = ['create', 'read', 'update','delete'];
                                            $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer'];
                                        @endphp

                                        @foreach ($permissionMaps as $index => $permissionMap)

                                            <div class="animated-checkbox mx-2" style="display:inline-block;">
                                                <label class="m-0">
                                                    <input type="checkbox" value="{{ $permissionMap . '_demandes'}}"
                                                        name="permissions[]" class="role">
                                                    <span class="label-text">{{ $permissionMapsFr[$index] }}</span>
                                                </label>
                                            </div>

                                        @endforeach

                                </td>
                            </tr>



                            <tr>
                                <td><strong>Demandes Externe</strong></td>
                                <td>

                                    <div class="animated-checkbox mx-2" style="display:inline-block;">
                                        <label class="m-0">
                                            <input type="checkbox" value="" name="" class="all-roles">
                                            <span class="label-text">Tous</span>
                                        </label>
                                    </div>

                                    @php
                                            //create_roles, read_roles, update_roles, delete_rolesextern
                                            $permissionMaps = ['create', 'read', 'update','delete'];
                                            $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer'];
                                        @endphp

                                        @foreach ($permissionMaps as $index => $permissionMap)

                                            <div class="animated-checkbox mx-2" style="display:inline-block;">
                                                <label class="m-0">
                                                    <input type="checkbox" value="{{ $permissionMap . '_demandes_extern'}}"
                                                        name="permissions[]" class="role">
                                                    <span class="label-text">{{ $permissionMapsFr[$index] }}</span>
                                                </label>
                                            </div>

                                        @endforeach

                                </td>
                            </tr>

                            <tr>
                                <td><strong>Bs</strong></td>
                                <td>

                                    <div class="animated-checkbox mx-2" style="display:inline-block;">
                                        <label class="m-0">
                                            <input type="checkbox" value="" name="" class="all-roles">
                                            <span class="label-text">Tous</span>
                                        </label>
                                    </div>

                                    @php
                                            //create_roles, read_roles, update_roles, delete_rolesextern
                                            $permissionMaps = ['create', 'read', 'update','delete','annulation'];
                                            $permissionMapsFr = ['ajouter', 'consulter', 'modifier','supprimer','annuler'];
                                        @endphp

                                        @foreach ($permissionMaps as $index => $permissionMap)

                                            <div class="animated-checkbox mx-2" style="display:inline-block;">
                                                <label class="m-0">
                                                    <input type="checkbox" value="{{ $permissionMap . '_bs'}}"
                                                        name="permissions[]" class="role">
                                                    <span class="label-text">{{ $permissionMapsFr[$index] }}</span>
                                                </label>
                                            </div>

                                        @endforeach

                                </td>
                            </tr>

                            <tr>
                                <td><strong>Inventaires</strong></td>
                                <td>
                                    <div class="animated-checkbox mx-2" style="display:inline-block;">
                                        <label class="m-0">
                                            <input type="checkbox" value="" name="" class="all-roles">
                                            <span class="label-text">Tous</span>
                                        </label>
                                    </div>


                                    @php
                                            //create_roles, read_roles, update_roles, delete_roles
                                            $permissionMaps = ['create', 'read', 'update','validation','verification','preparation'];
                                            $permissionMapsFr = ['ajouter', 'consulter', 'modifier','validation','verification','preparation'];
                                        @endphp

                                        @foreach ($permissionMaps as $index => $permissionMap)

                                            <div class="animated-checkbox mx-2" style="display:inline-block;">
                                                <label class="m-0">
                                                    <input type="checkbox" value="{{ $permissionMap . '_inventaires'}}"
                                                        name="permissions[]" class="role">
                                                    <span class="label-text">{{ $permissionMapsFr[$index] }}</span>
                                                </label>
                                            </div>

                                        @endforeach

                                </td>
                            </tr>

                        </tbody>
                    </table><!-- end of table -->

                    <div class="form-group">
                        <button type="submit" class="btn btn-default"><i class="fa fa-plus"></i>Enregistrer</button>
                    </div>
                    </fieldset>
                </form><!-- end of form -->




@endsection
