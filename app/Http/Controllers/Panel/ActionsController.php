<?php

namespace App\Http\Controllers\Panel;

use App\Models\CalendarExercise;
use App\Models\CalendarRecipe;
use App\Models\RecipeSupplement;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Resizer;
use Illuminate\Support\Str;
use App\Models\PanelModel;

class ActionsController extends Controller
{
    /**
     * @var $request Request
     */
    protected $request;
    // A model name obtained from the route
    protected $modelName;
    // The model object
    protected $model;
    // An id obtained from the route
    protected $id = 0;
    // Show items per page by default
    protected $perPageDefault = 20;

    /**
     * An action router
     * @param Request $request
     * @param $action
     * @param $modelName
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function act(Request $request, $action, $modelName, $id = 0)
    {
        $this->request = $request;
        $this->modelName = $modelName;
        $this->id = $id;

        switch($action){
            case 'show':
                return $this->show();
            break;
            case 'sort':
                return $this->sort();
            break;
            case 'create':
                return $this->create();
            break;
            case 'edit':
                return $this->edit();
            break;
            case 'gallerysort':
                return $this->sortGallery();
            break;
            case 'imageadd':
                return $this->addImage();
            break;
            case 'imagedrop':
                return $this->dropImage();
            break;
            case 'imagetitles':
                return $this->modityImageTitles();
            break;
            case 'save':
                return $this->save();
            break;
            case 'drop':
                return $this->drop();
            break;
            case 'bindexercise':
                return $this->modifyCalendarExercisesBinding();
            break;
            case 'unbindexercise':
                return $this->modifyCalendarExercisesBinding(true);
            break;
            case 'bindrecipe':
                return $this->modifyCalendarRecipesBinding();
            break;
            case 'unbindrecipe':
                return $this->modifyCalendarRecipesBinding(true);
            break;
            case 'bindsupplement':
                return $this->modifyRecipeSupplementsBinding();
            break;
            case 'unbindsupplement':
                return $this->modifyRecipeSupplementsBinding(true);
            break;
        }

        throw new NotFoundHttpException;
    }

    private function checkAccess(PanelModel $panelModel, $accessType = '')
    {
        if(!Auth::user()->hasAccess($panelModel, $accessType)) throw new \Exception('No access');
    }

    /**
     * Show a list of model items
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    protected function show()
    {
        $panelModels = PanelModel::all();

        $model = $this->factoryModel($this->modelName);

        $options = $this->getOptions($model);

        $currentPanelModel = $this->getPanelModel($model);

        $this->checkAccess($currentPanelModel, 'r');

        $perPage = (int) $this->request->get('q', $this->perPageDefault);

        $builder = !empty($currentPanelModel->sortable) ? $model::orderBy( 'ord', 'DESC') : $model::orderBy( $model->orderByDefault[0], $model->orderByDefault[1]);

        $model->modifyQueryBuilder($this->request, $builder);

        if($perPage < 0){
            $items = $builder->get();
        }else{
            $items = $builder->paginate($perPage);
        }

        $canCreate = Auth::user()->hasAccess($currentPanelModel, 'c');

        return view('panel.show.index', compact(
            'panelModels',
            'currentPanelModel',
            'model',
            'options',
            'items',
            'canCreate'
        ));
    }


    /**
     * Sort a list items
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function sort()
    {
        $model = $this->factoryModel($this->modelName);

        $currentPanelModel = $this->getPanelModel($model);

        $this->checkAccess($currentPanelModel, 'u');

        $ids = $this->request->get('ids', '');

        $ids = str_replace('i', '', $ids);

        if(empty($ids)) throw new \Exception;

        $ids = explode(',', $ids);

        $i = $model->whereIn('id', $ids)->max('ord');

        foreach($ids as $id){
            if($item = $model::where('id', '=', $id)->first()){
                $item->ord = $i;
                $item->update();
                $i--;
            }
        }

        return response()->json([

        ]);
    }

    /**
     * Create a new model item
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    protected function create()
    {
        $panelModels = PanelModel::all();

        $item = $this->factoryModel($this->modelName);

        $currentPanelModel = $this->getPanelModel($item);

        $this->checkAccess($currentPanelModel, 'c');

        $canCreate = Auth::user()->hasAccess($currentPanelModel, 'c');
        $canUpdate = Auth::user()->hasAccess($currentPanelModel, 'u');

        $options = $this->getOptions($item);

        return view('panel.edit.'.camel_case($this->modelName), compact(
            'panelModels',
            'currentPanelModel',
            'item',
            'options',
            'canCreate',
            'canUpdate'
        ));
    }

    /**
     * Edit model item
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    protected function edit()
    {
        $panelModels = PanelModel::all();

        $model = $this->factoryModel($this->modelName);

        $item = $model::findOrFail($this->id);

        $currentPanelModel = $this->getPanelModel($model);

        $this->checkAccess($currentPanelModel, 'r');

        $options = $this->getOptions($item);

        $canUpdate = Auth::user()->hasAccess($currentPanelModel, 'u');
        $canDelete = Auth::user()->hasAccess($currentPanelModel, 'd');

        return view('panel.edit.'.camel_case($this->modelName), compact(
            'panelModels',
            'currentPanelModel',
            'item',
            'options',
            'canUpdate',
            'canDelete'
        ));
    }

    protected function sortGallery()
    {
        $model = $this->factoryModel($this->modelName);

        $item = $model::findOrNew($this->id);

        $currentPanelModel = $this->getPanelModel($model);

        $this->checkAccess($currentPanelModel, 'u');

        $indexes = $this->request->input('indexes');
        $item->gallery = $this->request->input('gallery');
        $gallery = $item->getGallery();
        $galleryTitles = $this->request->input('galleryTitles');

        $indexes = array_map(function($index){
            return str_replace('i', '', $index);
        }, $indexes);

        $sortedGallery = [];

        if(!empty($indexes) && !empty($gallery) && count($gallery) == count($indexes)){
            foreach($indexes as $index){
                $sortedGallery[] = $gallery[$index];
            }
        }

        $item->setGallery($sortedGallery);
        $item->setAttribute('gallery_titles', Resizer::galleryTitlesString($galleryTitles));

        if (!empty($this->id)){
            $item->touch();

            $item->update();
        }

        return response()->json([
            'gallery'=>$item->gallery
        ]);
    }

    /**
     * Add an image to the model item
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    protected function addImage()
    {
        $model = $this->factoryModel($this->modelName);

        $item = $model::findOrNew($this->id);

        $currentPanelModel = $this->getPanelModel($model);

        $this->checkAccess($currentPanelModel, 'u');

        if($resizerConfig = Resizer::getConfig()) {

            $name = Str::random(24);

            Resizer::addImage($this->request->file('_image')->getPathname(), $name, empty($this->id), $model->getResizerConfigSet());

            $gallery = Resizer::gallery($this->request->input('gallery'));

            array_push($gallery, $name);

            $data = $this->request->all();

            $data['gallery'] = Resizer::galleryString($gallery);

            $item->fill($data);

            if (!empty($this->id)){
                $item->touch();

                $item->update($data);
            }
        }

        $data = [
            'item'=>$item,
            'imagesDir'=>Resizer::getImagesDir($this->id),
            'currentPanelModel'=>$this->getPanelModel($model),
            'canUpdate'=>Auth::user()->hasAccess($currentPanelModel, 'u'),
        ];

        $part = view('panel.edit._galleryItems', $data)->render();

        return response()->json([
            'part'=>$part,
            'gallery'=>$item->gallery
        ]);
    }

    /**
     * Delete an image from the model item
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Throwable
     */
    protected function dropImage()
    {
        $model = $this->factoryModel($this->modelName);

        $item = $model::findOrNew($this->id);

        $currentPanelModel = $this->getPanelModel($model);

        $this->checkAccess($currentPanelModel, 'u');

        $gallery = Resizer::gallery($this->request->input('gallery'));
        $titles = $this->request->input('galleryTitles');

        $index = $this->request->input('index', 0);

        // Unset element with given index in gallery images array
        if (!empty($gallery[$index])){
            // Delete image file(s) with given index
            Resizer::deleteImages($gallery[$index], empty($this->id), $model->getResizerConfigSet());

            unset($gallery[$index]);

            unset($titles[$index]);
        }

        $data = $this->request->all();

        $data['gallery'] = Resizer::galleryString($gallery);
        $data['gallery_titles'] = Resizer::galleryTitlesString($titles);

        $item->fill($data);

        if (!empty($this->id)){
            $item->touch();

            $item->update($data);
        }

        $data = [
            'item'=>$item,
            'imagesDir'=>Resizer::getImagesDir($this->id),
            'currentPanelModel'=>$this->getPanelModel($model),
            'canUpdate'=>Auth::user()->hasAccess($currentPanelModel, 'u'),
        ];

        $part = view('panel.edit._galleryItems', $data)->render();

        return response()->json([
            'part'=>$part,
            'gallery'=>$item->gallery
        ]);
    }

    protected function modityImageTitles()
    {
        $model = $this->factoryModel($this->modelName);

        $item = $model::findOrNew($this->id);

        $currentPanelModel = $this->getPanelModel($model);

        $this->checkAccess($currentPanelModel, 'u');

        $titles = Resizer::gallery($this->request->input('galleryTitles'));

        $data['gallery_titles'] = Resizer::galleryTitlesString($titles);

        $item->fill($data);

        if (!empty($this->id)){
            $item->touch();

            $item->update($data);
        }

        $data = [
            'item'=>$item,
            'imagesDir'=>Resizer::getImagesDir($this->id),
            'currentPanelModel'=>$this->getPanelModel($model),
            'canUpdate'=>Auth::user()->hasAccess($currentPanelModel, 'u'),
        ];

        $part = view('panel.edit._galleryItems', $data)->render();

        return response()->json([
            'part'=>$part,
            'gallery'=>$item->gallery
        ]);
    }

    /**
     * Save model item
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function save()
    {
        // Get a model to work with
        $model = $this->factoryModel($this->modelName);

        // Get current panel model (from the DB)
        $currentPanelModel = $this->getPanelModel($model);

        // Check current user access rights to create or update
        $this->checkAccess($currentPanelModel, (empty($this->id) ? 'c' : 'u'));

        // Validate input
        if($this->hasValidationRules($model)) $this->validate($this->request, $model->getValidationRules(), $model->getValidationMessages());

        // Get data from the request
        $data = $this->request->all();

        // Make the model item
        // Create a new one
        if(empty($this->id)) {
            $item = (new $model);
        // Update existed item
        }else{
            $item = $model::findOrFail($this->id);
        }

        // Fill the model with given data
        $item->fill($data);

        if(empty($this->id) && !empty($currentPanelModel->sortable)){
            $ord = $model->max('ord');
            $ord++;
            $item->ord = $ord;
        }

        // Do something before update an existed item
        if(method_exists($item, 'beforeUpdate') && !empty($this->id)) $item->beforeUpdate($data);

        // Do something before save an item
        if(method_exists($item, 'beforeSave')) $item->beforeSave($data);

        // Update an item modify time
        $item->touch();
        
        $item->save();

        // Move gallery images from temporary to the permanent location for just created item
        if(empty($this->id)) {
            if(array_key_exists('gallery', $data)) Resizer::moveToPermanentLocation($this->request->input('gallery'), $model->getResizerConfigSet());
        }

        // Do something after save an item
        if(method_exists($item, 'afterSave')) $item->afterSave($data);

        return response()->json([
            'location'=>url()->route('admin::act', [
                'action'=>'show',
                'modelName'=>$this->modelName
            ], false)
        ]);
    }

    /**
     * Delete model item
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function drop()
    {
        $model = $this->factoryModel($this->modelName);

        $currentPanelModel = $this->getPanelModel($model);

        $this->checkAccess($currentPanelModel, 'd');

        $item = $model::findOrFail($this->id);

        $item->delete();

        Resizer::deleteImages($item->gallery, false, $model->getResizerConfigSet());

        return response()->json([ 'location'=>url()->route('admin::act', ['action'=>'show', 'modelName'=>$this->modelName], false) ]);
    }

    protected function modifyCalendarExercisesBinding($unbind = false)
    {
        try {
            // Calendar
            $model = $this->factoryModel($this->modelName);

            $currentPanelModel = $this->getPanelModel($model);

            $this->checkAccess($currentPanelModel, 'u');

            // Current calendar item
            $calendar = $model::findOrFail($this->id);

            if($unbind){
                $binding = CalendarExercise::where('id', '=', $this->request->get('bid'))->first();

                if(!empty($binding)) $binding->delete();
            }else{
                $binding = new CalendarExercise();

                $binding->calendar_id = $this->id;
                $binding->exercise_id = $this->request->input('_exercise_id');
                $binding->type = $this->request->input('_exercise_type');

                $binding->save();
            }

            $view = view('panel.edit.calendarExercises', [
                'currentPanelModel' => $currentPanelModel,
                'binded' => $calendar->exercises()->get()
            ])->render();

            return response()->json([
                'view'=>$view
            ]);
        }catch (\Exception $e){
            throw new \Exception('Error while the binding modify');
        }
    }

    protected function modifyCalendarRecipesBinding($unbind = false)
    {
        try {
            // Calendar
            $model = $this->factoryModel($this->modelName);

            $currentPanelModel = $this->getPanelModel($model);

            $this->checkAccess($currentPanelModel, 'u');

            // Current calendar item
            $calendar = $model::findOrFail($this->id);

            if($unbind){
                $binding = CalendarRecipe::where('id', '=', $this->request->get('bid'))->first();

                if(!empty($binding)) $binding->delete();
            }else{
                $binding = new CalendarRecipe();

                $binding->meal_id = $this->request->input('_meal_id');
                $binding->calendar_id = $this->id;
                $binding->recipe_id = $this->request->input('_recipe_id');

                $binding->save();
            }

            $view = view('panel.edit.calendarRecipes', [
                'currentPanelModel' => $currentPanelModel,
                'item' => $calendar,
                'binded' => $calendar->recipes()->get()
            ])->render();

            return response()->json([
                'view'=>$view
            ]);
        }catch (\Exception $e){
            throw new \Exception('Error while the binding modify');
        }
    }

    protected function modifyRecipeSupplementsBinding($unbind = false)
    {
        try {
            // Recipe model
            $model = $this->factoryModel($this->modelName);

            $currentPanelModel = $this->getPanelModel($model);

            $this->checkAccess($currentPanelModel, 'u');

            // Current recipe item
            $recipe = $model::findOrFail($this->id);

            if($unbind){
                $binding = RecipeSupplement::where('id', '=', $this->request->get('bid'))->first();

                if(!empty($binding)) $binding->delete();
            }else{
                $binding = new RecipeSupplement();

                $binding->recipe_id = $this->id;
                $binding->supplement_id = $this->request->input('_supplement_id');
                $binding->weight = $this->request->input('_weight');

                $binding->save();
            }

            $view = view('panel.edit.recipeSupplements', [
                'currentPanelModel' => $currentPanelModel,
                'binded' => $recipe->supplements()->get()
            ])->render();

            return response()->json([
                'view'=>$view
            ]);
        }catch (\Exception $e){
            echo $e->getFile().' '.$e->getLine().' '.$e->getMessage();
            throw new \Exception('Error while the binding modify');
        }
    }

    /**
     * @param $object \Illuminate\Database\Eloquent\Model
     * @return bool
     */
    private function hasOptions($object)
    {
        return (method_exists($object, 'getOptions') && method_exists($object, 'setOptions')) ? true : false;
    }

    /**
     * @param $object \Illuminate\Database\Eloquent\Model
     * @return array
     */
    private function getOptions($object)
    {
        return method_exists($object, 'getOptions') ? $object->getOptions() : [];
    }

    /**
     * @param $object \Illuminate\Database\Eloquent\Model
     * @return bool
     */
    private function hasValidationRules($object)
    {
        return method_exists($object, 'getValidationRules') ? true : false;
    }
}