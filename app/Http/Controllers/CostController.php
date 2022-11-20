<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\CurrencyValue;
use App\Models\Material;
use App\Models\MaterialRate;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Vinkla\Hashids\Facades\Hashids;

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
                $id = Hashids::encode($d->id);
                return '<a class="btn btn-sm bg-primary-warning" href="'. route('cost.show.calculate', $id) .'">'. __('view.calculate') .'</a>
                    <button class="btn btn-sm bg-primary-blue" type="button">'. __('view.edit') .'</button>';
            })
            ->rawColumns(['created_at', 'action'])
            ->make(true);
    }

    /**
     * Function to show calculate form
     * @return Renderable
     */
    public function indexCalculate($id)
    {
        $uid = Hashids::decode($id)[0];
        $data = Cost::find($uid);
        $title = __('view.calculate_cost');
        $material_groups = Material::all();
        $process_groups = Process::all();
        $currency_group = CurrencyValue::GROUP_WITH_ID;
        $currency_types = CurrencyValue::TYPES_WITH_ID;
        $currency = [];
        foreach ($currency_types as $type) {
            foreach ($currency_group as $group) {
                $currency[] = [
                    'id' => $group['id'] . '-' . $type['id'],
                    'name' => $group['name'] . ' ' . $type['name']
                ];
            }
        }
        return view('adminLte.pages.cost.calculate', compact('data', 'title', 'material_groups', 'currency', 'currency_group', 'currency_types', 'process_groups'));
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
     * Function to submit calculate cost
     * @param int id
     */
    public function submitCalculate(Request $request, $id)
    {
        return response()->json(['message' => 'Success', 'data' => ['value' => $request->all(), 'id' => $id]]);
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
