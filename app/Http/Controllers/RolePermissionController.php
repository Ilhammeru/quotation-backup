<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RolePermissionController extends Controller
{
    /**
     * Function to render permission list
     */
    public function permissions()
    {
        $pageTitle = setPageTitle(__('view.permissions'));
        $title = __('view.permissions');
        return view('adminLte.pages.setting.permissions.index', compact('pageTitle', 'title'));
    }

    /**
     * Function to show all data permission to datatablse
     * @return DataTables
     */
    public function ajaxPermission()
    {
        $data = Permission::all();
        return DataTables::of($data)
            ->addColumn('action', function($d) {
                return '<button class="btn btn-sm bg-primary-warning" type="button">'. __('view.edit') .'</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
