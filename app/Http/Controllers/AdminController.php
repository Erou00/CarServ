<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\Categorie;
use App\Models\Magasin;
use App\Models\Role;
use App\Models\Service;
use App\Models\SousMagasin;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    //
    public function index()
    {

        $roles = Role::whereNotIn('name', ['super_admin', 'admin', 'user'])->get();
        return view('dashboard.admins.index', compact('roles'));

    }// end of index

    public function data()
    {
        $admins = User::whenRolesId(request()->role_id)
                        ->whenServiceId(request()->service_id);

        return DataTables::of($admins)
            ->addColumn('record_select', 'dashboard.admins.data_table.record_select')
            ->addColumn('roles', function (User $admin) {
                return view('dashboard.admins.data_table.roles', compact('admin'));
            })
            ->editColumn('created_at', function (User $admin) {
                return $admin->created_at->format('Y-m-d');
            })
            ->addColumn('actions', 'dashboard.admins.data_table.actions')
            ->rawColumns(['record_select', 'roles', 'actions'])
            ->toJson();

    }// end of data

    public function create()
    {

        $roles = Role::whereNotIn('name', ['super_admin'])->get();
        $categories = Categorie::all();
        $magasins = Magasin::all();
        $sousmagasins = SousMagasin::all();


        return view('dashboard.admins.create', compact('roles',
        'categories','magasins','sousmagasins'));

    }// end of create

    public function store(AdminRequest $request)
    {
        $requestData = $request->validated();
        //  dd($request->all());
        $requestData['password'] = bcrypt($request->password);

        $admin = User::create($requestData);
            # code...

        $admin->attachRoles($request->role_id);
        $admin->categories()->attach($request->categorie_id);

        $admin->sousmagasins()->attach($request->sousmagasin_id);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('utilisateurs.index');

    }// end of store

    public function edit($id)
    {

        $admin = User::findOrFail($id);
        $roles = Role::all();
        $categories = Categorie::all();
        $magasins = Magasin::all();

        return view('dashboard.admins.edit', compact('admin','magasins','roles', 'categories'));

    }// end of edit

    public function update(AdminRequest $request, $id)
    {


        $admin = User::findOrFail($id);
        $admin->update($request->validated());

        $admin->roles()->sync($request->role_id);

        $admin->categories()->sync($request->categorie_id);
        $admin->sousmagasins()->sync($request->sousmagasin_id);


        session()->flash('success', __('Modifier avec succés'));
        return redirect()->route('utilisateurs.index');

    }// end of update

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return response(__('site.deleted_successfully'));

    }// end of destroy

    public function bulkDelete()
    {
        foreach (json_decode(request()->record_ids) as $recordId) {

            $admin = User::FindOrFail($recordId);
            $this->delete($admin);

        }//end of for each

        session()->flash('success', __('site.deleted_successfully'));
        return response(__('site.deleted_successfully'));

    }// end of bulkDelete

    private function delete(User $admin)
    {
        $admin->delete();

    }// end of delete


}
