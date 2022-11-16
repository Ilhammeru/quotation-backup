<?php

/**
* @author Ilham Gumilang <gumilang.dev@gmail.com>
* date 20221116
*/

namespace App\Http\Controllers;

use App\Http\Services\CurrencyService;
use App\Models\CurrencyValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CurrencyValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $segment_key = getUrlSegment(3);
        $type = getUrlSegment(4);
        $group = getUrlSegment(5);
        $validate = validate_route();
        if ($validate) {
            $ajax_url = route('currency.ajax', ['type' => $type, 'group' => $group]);
            $store_url = route('currency.store', ['type' => $type, 'group' => $group]);
            $pageTitle = setPageTitle(__('view.currency_title', ['name' => strtoupper($validate['group']) . ' ' . ucfirst($validate['type'])]));
            $title = __('view.currency_title', ['name' => strtoupper($validate['group']) . ' ' . ucfirst($validate['type'])]);
            return view('adminLte.pages.currency.index', compact('type', 'group', 'pageTitle', 'title', 'ajax_url', 'store_url'));
        }
    }

    /**
     * Function to get data for datatable
     * @param string $type
     * @param string $group
     * 
     * @return DataTables
     * 
     */
    public function ajax($type, $group)
    {
        $service = new CurrencyService();
        $group_id = $service->getGroupId($group);
        $type_id = $service->getTypeId($type);
        $data = CurrencyValue::where('currency_type_id', $type_id)
            ->where('currency_group_id', $group_id)
            ->get();
        return DataTables::of($data)
            ->editColumn('period', function ($d) {
                return date('M y', strtotime($d->period));
            })
            ->editColumn('value', function($d) {
                return number_format($d->value, 0, ',', '.');
            })
            ->addColumn('action', function($d) use($type, $group) {
                $edit_url = route('currency.show', ['type' => $type, 'group' => $group, 'id' => $d->id]);
                return '<button class="btn btn-sm bg-primary-warning" id="btn-edit-currency-'. $d->id .'" type="button" data-url="'. $edit_url .'" onclick="editItem('. $d->id .')">'. __('view.edit').'</button>
                    <button class="btn btn-sm bg-primary-danger" type="button" onclick="deleteItem('. $d->id .')">'. __('view.delete').'</button>';
            })
            ->rawColumns(['period', 'value', 'action'])
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
        DB::beginTransaction();
        try {
            $route = validate_route();
            if (!$route) {
                return response()->json(['message' => 'Failed to save data'], 500);
            }

            $group_id = $route['group_id'];
            $type_id = $route['type_id'];

            // validation
            $validate = Validator::make($request->all(), [
                'period' => 'required',
                'value' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $model = new CurrencyValue();
            $model->currency_type_id = $type_id;
            $model->currency_group_id = $group_id;
            $model->period = date('Y-m-d', strtotime('01-' . $request->period));
            $model->value = $request->value;
            $model->save();

            DB::commit();
            return response()->json(['message' => 'Success create currency data']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CurrencyValue  $currencyValue
     * @return \Illuminate\Http\Response
     */
    public function show($type, $group, $id)
    {
        try {
            $data = CurrencyValue::find($id);
            $update_url = route('currency.update', ['type' => $type, 'group' => $group, 'id' => $id]);
            $data->period = date('m-Y', strtotime($data->period));
            return response()->json(['message' => 'Success get detail', 'data' => $data, 'url' => $update_url]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CurrencyValue  $currencyValue
     * @return \Illuminate\Http\Response
     */
    public function edit(CurrencyValue $currencyValue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CurrencyValue  $currencyValue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $type, $group, $id)
    {
        DB::beginTransaction();
        try {
            $route = validate_route();
            if (!$route) {
                return response()->json(['message' => 'Failed to save data'], 500);
            }

            $group_id = $route['group_id'];
            $type_id = $route['type_id'];

            // validation
            $validate = Validator::make($request->all(), [
                'period' => 'required',
                'value' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $model = CurrencyValue::find($id);

            $period = date('Y-m-d', strtotime('01-' . $request->period));
            if ($model->period != $period) {
                $check = CurrencyValue::where('period', $period)->first();
                if ($check) {
                    return response()->json(['message' => 'Data with this period is already exists in database'], 500);
                }
            }

            $model->period = $period;
            $model->value = $request->value;
            $model->save();

            DB::commit();
            return response()->json(['message' => 'Success create currency data']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CurrencyValue  $currencyValue
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = CurrencyValue::find($id);
            $data->delete();

            return response()->json(['message' => 'Success delete currency']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
