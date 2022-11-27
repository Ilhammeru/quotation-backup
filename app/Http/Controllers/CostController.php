<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\CostMaterial;
use App\Models\CostMaterialDetail;
use App\Models\CostProcessDetail;
use App\Models\CostPurchase;
use App\Models\CostPurchaseDetail;
use App\Models\CurrencyValue;
use App\Models\Material;
use App\Models\MaterialRate;
use App\Models\Process;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                $hide = '';
                if (
                    count($d->materials) > 0 ||
                    count($d->process) > 0 ||
                    count($d->purchases) > 0
                ) {
                    $hide = 'd-none';
                }

                $calculate = '<a class="btn btn-sm bg-primary-warning '. $hide .'" href="'. route('cost.show.calculate', $id) .'">'. __('view.calculate') .'</a>';
                if ($hide == 'd-none') {
                    $edit = '<a class="btn btn-sm bg-primary-blue" href="'. route('cost.edit.calculate', $id) .'">'. __('view.edit') .'</a>';
                } else {
                    $url = route('cost.update', $d->id);
                    $edit = '<button class="btn btn-sm bg-primary-blue" type="button" onclick="editItem(`'. $d->name .'`, `'. $d->number .'`, `'. $url .'`)">'. __('view.edit') .'</a>';
                }

                return $calculate . $edit;
            })
            ->rawColumns(['created_at', 'action'])
            ->make(true);
    }

    /**
     * Function to show calculate form to edit
     * @return Renderable
     */
    public function editCalculate($id)
    {
        $uid = Hashids::decode($id)[0];
        $data = Cost::select(
                'name', 'number', 'total_cost',
                'material_cost', 'process_cost',
                'purchase_cost', 'id'  
            )
            ->with([
                'materials.rate.material',
                'materials.rate.materialSpec',
                'materials.currency',
                'process.rate.code',
                'process.rate.group',
                'process.cost',
                'purchases.currencyValue'
            ])
            ->find($uid);
        $data->purchase_cost = $data->purchase_cost != 0 ? number_format($data->purchase_cost, 3, '.', '') : 0;
        $data->material_cost = $data->material_cost != 0 ? number_format($data->material_cost, 3, '.', '') : 0;
        $data->process_cost = $data->process_cost != 0 ? number_format($data->process_cost, 3, '.', '') : 0;
        $data->total_cost = $data->total_cost != 0 ? number_format($data->total_cost, 3, '.', '') : 0;

        $type_form = 'edit';
        $materials = $data->materials;
        $process = $data->process;
        $purchases = $data->purchases;
        $title = __('view.calculate_cost');
        $material_groups = Material::all();
        $process_groups = Process::all();
        $currency_group = CurrencyValue::GROUP_WITH_ID;
        $currency_types = CurrencyValue::TYPES_WITH_ID;
        $materials = collect($materials)->map(function($item) {
            $item->total = number_format($item->total, 3, '.', '');
            return $item;
        })->all();
        $process = collect($process)->map(function($item) {
            $item->total = number_format($item->total, 3, '.', '');
            return $item;
        })->all();
        $purchases = collect($purchases)->map(function($item) use(
            $currency_group,
            $currency_types,
        ) {
            collect($currency_group)->map(function($i) use($item) {
                if ($item->currencyValue->currency_group_id == $i['id']) {
                    $item->currencyValue->currency_group_name = $i['name'];
                }
            });

            collect($currency_types)->map(function($y) use ($item) {
                if ($item->currencyValue->currency_type_id == $y['id']) {
                    $item->currencyValue->currency_type_name = $y['name'];
                }
            });
            $item->total = number_format($item->total,3,'.','');
            return $item;
        })->all();
        $currency = [];
        // return $data;
        foreach ($currency_types as $type) {
            foreach ($currency_group as $group) {
                $currency[] = [
                    'id' => $group['id'] . '-' . $type['id'],
                    'name' => $group['name'] . ' ' . $type['name']
                ];
            }
        }
        return view('adminLte.pages.cost.calculate', compact(
            'data', 'title', 'material_groups', 'currency',
            'currency_group', 'currency_types', 'process_groups',
            'materials', 'process', 'purchases', 'type_form'
        ));
    }

    /**
     * Function to show calculate form
     * @return Renderable
     */
    public function indexCalculate($id)
    {
        $uid = Hashids::decode($id)[0];
        $data = Cost::select(
                'name', 'number', 'total_cost',
                'material_cost', 'process_cost',
                'purchase_cost', 'id'  
            )
            ->with([
                'materials.rate',
                'materials.currency',
                'process.rate',
                'purchases.currency'
            ])
            ->find($uid);
        
        $type_form = 'create';
        $materials = $data->materials;
        $process = $data->process;
        $purchases = $data->purchases;
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
        return view('adminLte.pages.cost.calculate', compact(
            'data', 'title', 'material_groups', 'currency',
            'currency_group', 'currency_types', 'process_groups',
            'materials', 'process', 'purchases', 'type_form'
        ));
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
     * Function to download as excel
     * @param int id
     * @return Downloadable
     */
    public function download($id)
    {
        try {
            $data = Cost::find($id);
            $filename = implode('', explode(' ', $data->name)) . '-' . date('YmdHis') . '.xlsx';
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('cost_template.xlsx');
            $worksheet = $spreadsheet->getActiveSheet();

            $worksheet->getCell('C3')->setValue($data->number);
            $worksheet->getCell('C5')->setValue($data->name);
            $worksheet->getCell('G10')->setValue(number_format($data->material_cost, 3, '.', ','));
            $worksheet->getCell('G11')->setValue(number_format($data->process_cost, 3, '.', ','));
            $worksheet->getCell('G12')->setValue(number_format($data->purchase_cost, 3, '.', ','));
            $worksheet->getCell('G13')->setValue(number_format($data->total_cost, 3, '.', ','));

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('download/' . $filename);
            return response()->download('download/' . $filename);
        } catch (\Throwable $th) {
            return back()->with(['error_message' => $th->getMessage()]);
        }
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
        DB::beginTransaction();
        try {
            // return response()->json(['message' => 'oke', 'data' => $request->all()]);
            $summary = $request->summary;
    
            if ($request->material) {
                collect($request->material)->map(function($item) use($id) {
                    CostMaterial::updateOrCreate(
                        [
                            'cost_id' => $id,
                            'material_rate_id' => $item['material_rate_id'],
                            'material_currency_value_id' => $item['material_currency_value_id'],
                        ],
                        [
                            'part_no' => $item['part_no'],
                            'part_name' => $item['part_name'],
                            'exchange_rate' => $item['exchange_rate'],
                            'usage_part' => $item['usage_part'],
                            'over_head' => $item['over_head'],
                            'total' => $item['total'],
                        ]
                    );

                    return [
                        'cost_id' => $id,
                        'part_no' => $item['part_no'],
                        'part_name' => $item['part_name'],
                        'material_rate_id' => $item['material_rate_id'],
                        'material_currency_value_id' => $item['material_currency_value_id'],
                        'exchange_rate' => $item['exchange_rate'],
                        'usage_part' => $item['usage_part'],
                        'over_head' => $item['over_head'],
                        'total' => $item['total'],
                    ];
                })->all();
            }
    
            if ($request->process) {
                collect($request->process)->map(function($item) use($id) {
                    CostProcessDetail::updateOrCreate(
                        [
                            'cost_id' => $id,
                            'process_rate_id' => $item['process_rate_id'],
                        ],
                        [
                            'part_no' => $item['part_no'],
                            'part_name' => $item['part_name'],
                            'cycle_time' => $item['cycle_time'],
                            'over_head' => $item['over_head'],
                            'total' => $item['total'],
                        ]
                    );
                    return [
                        'cost_id' => $id,
                        'part_no' => $item['part_no'],
                        'part_name' => $item['part_name'],
                        'process_rate_id' => $item['process_rate_id'],
                        'cycle_time' => $item['cycle_time'],
                        'over_head' => $item['over_head'],
                        'total' => $item['total'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                })->all();
            }
    
            if ($request->purchase) {
                collect($request->purchase)->map(function($item) use($id) {
                    CostPurchase::updateOrCreate(
                        [
                            'cost_id' => $id,
                            'currency' => $item['currency'],
                            'currency_type' => $item['currency_type'],
                            'currency_value_id' => $item['currency_value_id'],
                        ],
                        [
                            'over_head' => $item['over_head'],
                            'part_name' => $item['part_name'],
                            'part_no' => $item['part_no'],
                            'quantity' => $item['quantity'],
                            'cost_value' => $item['cost'],
                            'total' => $item['total'],
                        ]
                    );
                    return [
                        'cost_id' => $id,
                        'currency' => $item['currency'],
                        'currency_type' => $item['currency_type'],
                        'currency_value_id' => $item['currency_value_id'],
                        'part_name' => $item['part_name'],
                        'part_no' => $item['part_no'],
                        'over_head' => $item['over_head'],
                        'quantity' => $item['quantity'],
                        'cost_value' => $item['cost'],
                        'total' => $item['total'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                })->all();
            }

            // delete unnecessary item
            if ($request->delete_id_material) {
                $exp_id_m = explode(',', $request->delete_id_material);
                for ($m = 0; $m < count($exp_id_m); $m++) {
                    CostMaterial::find($exp_id_m[$m])->delete();
                }
            }
            if ($request->delete_id_process) {
                $exp_id_p = explode(',', $request->delete_id_process);
                for ($p = 0; $p < count($exp_id_p); $p++) {
                    CostProcessDetail::find($exp_id_p[$p])->delete();
                }
            }
            if ($request->delete_id_purchase) {
                $exp_id_pr = explode(',', $request->delete_id_purchase);
                for ($pr = 0; $pr < count($exp_id_pr); $pr++) {
                    CostPurchase::find($exp_id_pr[$pr])->delete();
                }
            }
    
            $summary_req = $request->summary;
            $summary = [
                'name' => $summary_req['mother_part_name'],
                'number' => $summary_req['mother_part_no'],
                'total_cost' => $summary_req['total'],
                'material_cost' => $summary_req['material'],
                'process_cost' => $summary_req['process'],
                'purchase_cost' => $summary_req['purchase'],
                'updated_at' => Carbon::now()
            ];
            Cost::where('id', $id)
                ->update($summary);

            DB::commit();
            return response()->json(['message' => 'Success Calculate Cost', 'url' => route('cost.index')]);
        } catch (\Throwable $th) {
            DB::rollBack();
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
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'number' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => 'Please fill the required form'], 500);
            }

            $model = Cost::find($id);
            $model->name = $request->name;
            $model->number = $request->number;
            $model->save();

            return response()->json(['message' => 'Success update Cost']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Cost::find($id);
            $data->delete();

            return response()->json(['message' => 'Success delete data', 'url' => route('cost.index')]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to delete this data'], 500);
        }
    }
}
