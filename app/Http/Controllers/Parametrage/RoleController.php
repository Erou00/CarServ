<?php

namespace App\Http\Controllers\Parametrage;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read_roles')->only(['index']);
        $this->middleware('permission:create_roles')->only(['create', 'store']);
        $this->middleware('permission:update_roles')->only(['edit', 'update']);
        $this->middleware('permission:delete_roles')->only(['delete', 'bulk_delete']);

    }// end of __construct
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.parametrage.roles.index');

    }// end of index

    public function data()
    {
        $roles = Role::whereNotIn('name', ['super_admin', 'user']);

        return DataTables::of($roles)
            ->addColumn('record_select', 'dashboard.parametrage.roles.data_table.record_select')
            ->editColumn('created_at', function (Role $role) {
                return $role->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'dashboard.parametrage.roles.data_table.actions')
            ->rawColumns(['record_select', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {
        return view('dashboard.parametrage.roles.create');

    }// end of create

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->only(['name']));
        if ($request->permissions) {
            # code...
            $role->attachPermissions($request->permissions);

        }

        session()->flash('success', __('Ajouter avec succès'));
        return redirect()->route('roles.index');

    }// end of store

    public function edit(Role $role)
    {

        return view('dashboard.parametrage.roles.edit', compact('role'));

    }// end of edit

    public function update(RoleRequest $request, Role $role)
    {
        //dd($role);
        $role->update($request->only(['name']));
        $role->syncPermissions($request->permissions);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->back();

    }// end of update

    public function destroy(Role $role)
    {
        $this->delete($role);
        session()->flash('success', __('site.deleted_successfully'));
        return response(__('site.deleted_successfully'));

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $role = Role::FindOrFail($recordId);
            $this->delete($role);

        }//end of for each

        session()->flash('success', __('site.deleted_successfully'));
        return response(__('site.deleted_successfully'));

    }// end of bulkDelete

    private function delete(Role $role)
    {
        $role->delete();

    }// end of delete

}//end of controller
