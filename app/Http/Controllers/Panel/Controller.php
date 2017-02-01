<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller as BaseController;
use App;
use App\Models\PanelModel;

class Controller extends BaseController
{
    /**
     * Factory a model object by its public name (URL alias)
     * @param string $modelName
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function factoryModel($modelName = '')
    {
        $modelClassName = studly_case($modelName);

        $modelClassPath = '\App\Models\\'.$modelClassName;

        if(!class_exists($modelClassPath)) throw new \Exception('Model '.$modelClassName.' does not exist');

        $model = App::make($modelClassPath);

        return $model;
    }

    /**
     * Get PanelObject model item by model object
     * @param string $model
     * @return PanelModel
     */
    protected function getPanelModel($model)
    {
        $modelName = $this->getPublicModelName($model);

        return PanelModel::where('public_model_name', '=', $modelName)->firstOrFail();
    }

    /**
     * Get model's public name (URL alias)
     * @param $model
     * @return string
     */
    protected function getPublicModelName($model)
    {
        return snake_case(class_basename($model));
    }
}