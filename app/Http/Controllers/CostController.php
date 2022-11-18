<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = setPageTitle(__('view.cost_tmmin'));
        $title = __('view.cost_tmmin');
        return view('adminLte.pages.cost.index', compact('pageTitle', 'title'));
    }

    /**
     * Function to show data for datatables
     * 
     * @return DataTables
     */
    public function ajax()
    {
        $data = Cost::all();
        return DataTables::of($data)
            ->editColumn('created_at', function($d) {
                return date('d/m/Y', strtotime($d->created_at));
            })
            ->addColumn('action', function($d) {
                return '<button class="btn btn-sm bg-primary-warning" type="button">'. __('view.calculate') .'</button>
                    <button class="btn btn-sm bg-primary-blue" type="button">'. __('view.edit') .'</button>';
            })
            ->rawColumns(['created_at', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'number' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $model = new Cost();
            $model->name = $request->name;
            $model->number = $request->number;
            $model->save();

            return response()->json(['message' => 'Success create Cost']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
