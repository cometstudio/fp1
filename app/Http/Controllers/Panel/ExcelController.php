<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use Excel;
use Auth;

class ExcelController extends Controller
{
    public function export(Request $request, $modelName, $format = 'csv')
    {
        $model = $this->factoryModel($modelName);

        $currentPanelModel = $this->getPanelModel($model);

        if(!Auth::user()->hasAccess($currentPanelModel, 'r')) throw new \Exception('No access');

        $builder = !empty($currentPanelModel->sortable) ? $model::orderBy( 'ord', 'DESC') : $model::orderBy( 'id', 'DESC');

        $model->modifyQueryBuilder($request, $builder, [
            'phone',
        ]);

        $data = $builder->get()->toArray();

        $data = array_filter($data);

        $filename = date('d-m-Y_H-i-s');

        $location = env('APP_URL').'/exports';

        Excel::create($filename, function($excel) use ($data, $filename) {
            $excel->sheet('Sheet1', function($sheet) use ($data) {
                // or fromModel
                $sheet->fromModel($data);
            });
        })->store('csv', public_path('exports'));

        return response()->json([
            'location'=>$location.'/'.$filename.'.csv'
        ]);
    }
}
