<?php

namespace Fpaipl\Panel\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class PanelController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $model;
    public $datatable;
    public $modelName;
    public $messages;
    public $fields;
    public $param;
    public $returnURL;

    /**
     * Constructor for the PanelController class.
     *
     * Initializes instance variables with the given parameters. These instance variables include
     * a model, a datatable, a parameter, a return URL, a list of messages, a model name, and fields.
     * The list of messages, model name, and fields are extracted from the datatable.
     *
     * @param mixed $datatable A datatable which contains a collection of data.
     * @param mixed $model The model associated with the controller.
     * @param mixed $param A parameter used for routing and finding specific instances.
     * @param string $returnURL The URL where the user should be redirected after an operation.
     */
    public function __construct($datatable, $model, $param, $returnURL)
    {
        $this->model = $model;
        $this->datatable = $datatable;
        $this->param = Str::lower($param);
        $this->returnURL = $returnURL;
        
        $this->messages = $datatable->getMessages();
        $this->modelName = $datatable->getModelName();
        $this->fields = $datatable->getfields()->toArray();
    }

    /**
     * Method that handles the indexing of a model type.
     *
     * This method first checks if the model type can be indexed, as specified by the 
     * INDEXABLE property on the model itself. If the model type cannot be indexed, 
     * it will respond with a 'Method Not Allowed' error.
     *
     * If the model is indexable, the method returns a view of the model index page. 
     * The view is named 'panel::lists.index'. 
     *
     * The parameters for the view include the model class, the class of the datatable, 
     * a list of messages for various user interactions, and the model name.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\Response The response containing the view of the model index page.
     */
    public function index(Request $request)
    {
        if (!$this->model::INDEXABLE()) {
            $this->methodNotAllowed($request);
        }

        return view('panel::lists.index', [
            'model' => $this->model,
            'datatable' => get_class($this->datatable),
            'messages' => $this->messages,
            'modelName' => $this->modelName,
        ]);
    }

    /**
     * Method that handles the creation of a new model instance.
     *
     * This method first checks if the model type can be created, as specified by the 
     * CREATEABLE property on the model itself. If the model type cannot be created, 
     * it will respond with a 'Method Not Allowed' error.
     *
     * If the model is createable, the method returns a view of the model creation form. 
     * The view is named 'panel::forms.model-crud'. Since this is a creation form, the 
     * 'model' parameter for the view is set to null. 
     *
     * The other parameters for the view include the type of form (in this case, 'create'), 
     * the fields of the model, the model name, and a list of messages for various user 
     * interactions.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\Response The response containing the view of the creation 
     * form, which will be used to input data for a new model instance.
     */
    public function create(Request $request)
    {
        if (!$this->model::CREATEABLE()) {
            $this->methodNotAllowed($request);
        }

        $modelInstance = null;
        if ($request->has('duplicate')) {
            $duplicateKey = $request->input('duplicate');
            $originalModel = $this->model::where((new ($this->model))->getRouteKeyName(), $duplicateKey)->first();
            if ($originalModel) {
                $modelInstance = $originalModel->replicate();
            }
        }        

        return view('panel::forms.model-crud', [
            'model' => $modelInstance,
            'formType' => __FUNCTION__,
            'fields' => $this->fields,
            'modelName' => $this->modelName,
            'messages' => $this->messages
        ]);
    }

    public function duplicate(Request $request)
    {
        if (!$this->model::EDITABLE()) {
            $this->methodNotAllowed($request);
        }

        return view('panel::forms.model-crud', [
            'model' => $this->getModelInstance($request)->firstOrFail(),
            'formType' => __FUNCTION__,
            'fields' => $this->fields,
            'modelName' => $this->modelName,
            'messages'  => $this->messages
        ]);
    }


    /**
     * Method that handles the editing of a specific model instance.
     *
     * This method first checks if the model type is editable, as specified by the 
     * EDITABLE property on the model itself. If the model type is not editable, 
     * it will respond with a 'Method Not Allowed' error.
     *
     * If the model is editable, the method will retrieve the specific instance of the 
     * model that needs to be edited using the `getModelInstance` method. It does this 
     * by using the parameters provided in the request to find the specific instance.
     *
     * Once the instance is retrieved, the method returns a view of the model editing 
     * form with the instance data pre-filled in. The view is named 'panel::forms.model-crud'. 
     * The parameters for the view include the model instance to be edited, the type of 
     * form (in this case, 'edit'), the fields of the model, the model name, a list of 
     * messages for various user interactions, and the current page number from the request.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\Response The response containing the view of the edit form, 
     * populated with the model instance data.
     */
    public function edit(Request $request)
    {
        if (!$this->model::EDITABLE()) {
            $this->methodNotAllowed($request);
        }

        return view('panel::forms.model-crud', [
            'model' => $this->getModelInstance($request)->firstOrFail(),
            'formType' => __FUNCTION__,
            'fields' => $this->fields,
            'modelName' => $this->modelName,
            'messages'  => $this->messages,
            'current_page_no' => $request->page
        ]);
    }

    /**
     * Display the specified resource.
     *
     * The "show" function is used to retrieve and display a specific record 
     * of a model from the database. It receives an HTTP request as an input,
     * gets the corresponding model instance based on the request parameters,
     * and returns a view with the model data. 
     *
     * If the model is not viewable (as determined by the VIEWABLE static 
     * method on the model), this method will return a "Method Not Allowed" response.
     *
     * @param  \Illuminate\Http\Request  $request
     *     An instance of the HTTP request sent by the user. It contains 
     *     the necessary parameters to identify the specific model instance 
     *     that should be retrieved and displayed.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *     This method will return one of two possible responses:
     *     - If the model is viewable, it will return a view that displays 
     *       the model's data. The view is named 'model-crud', and it 
     *       is located within the 'panel::forms' directory. The view is 
     *       returned with an array of data that includes the model instance, 
     *       the type of form being used, the fields of the model, the model name, 
     *       messages to be displayed, and the current page number.
     *     - If the model is not viewable, it will return a redirect response 
     *       back to the previous location with a 'toast' flash message that 
     *       indicates the method was not allowed.
     */
    public function show(Request $request)
    {
        if (!$this->model::VIEWABLE()) {
            $this->methodNotAllowed($request);
        }
        
        return view('panel::forms.model-crud', [
            'model' => $this->getModelInstance($request)->firstOrFail(),
            'formType' => __FUNCTION__,
            'fields' => $this->fields,
            'modelName' => $this->modelName,
            'messages'  => $this->messages,
            'current_page_no' => $request->page
        ]);
    }

    /**
     * Displays an advanced deletion form for certain models.
     *
     * This method fetches a model instance based on 'id' or 'slug', depending on the model type. 
     * It then returns a view that displays an advanced deletion form for the model instance.
     *
     * @param  \Illuminate\Http\Request  $request The incoming HTTP request.
     * @return \Illuminate\Http\Response A view that displays an advanced deletion form for a model instance.
     */
    public function advance_delete(Request $request)
    {
        $model = $this->getModelInstance($request)->firstOrFail();

        return view('panel::forms.model-crud', [
            'model' => $model,
            'modelClass' => $this->model,
            'datatable' => get_class($this->datatable),
            'formType' => __FUNCTION__,
            'modelName' => $this->modelName,
            'returnURL' => $this->returnURL
        ]);
    }

    /**
     * Get an instance of a model based on 'id' or 'slug'.
     *
     * This method checks the model type and fetches a model instance from the database 
     * based on 'id' or 'slug', depending on the model type. The instance is not yet retrieved.
     *
     * @param  \Illuminate\Http\Request  $request The incoming HTTP request.
     * @return \Illuminate\Database\Eloquent\Builder An Eloquent query builder instance.
     */
    private function getModelInstance(Request $request){
        // dd($request->route()->parameters());

        // Check if the model uses the SoftDeletes trait
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($this->model))) {
            // If it does, include trashed results
            $query = $this->model::withTrashed();
        } else {
            // Otherwise, just use the model
            $query = $this->model::query();
        }

        if (in_array($this->model, [
            'App\Models\User', // uuid
            'Fpaipl\Authy\Models\Profile', // id
            'Fpaipl\Authy\Models\Account', // id
            
            'Fpaipl\Prody\Models\Supplier', // id
            'Fpaipl\Prody\Models\Material', // id
            'Fpaipl\Prody\Models\Unit', // id
            'Fpaipl\Prody\Models\Tax', // id

            'Fpaipl\Prody\Models\Overhead', // id
            'Fpaipl\Prody\Models\Fixedcost', // sid
            'Fpaipl\Prody\Models\Consumable', // sid

            // 'Fpaipl\Prody\Models\Brand', // id
            
            'Fpaipl\Brandy\Models\Party', // sid
            'Fpaipl\Brandy\Models\Employee', // sid

            'Fpaipl\Shopy\Models\Payment', // id
            'Fpaipl\Shopy\Models\Delivery', // id
            'Fpaipl\Shopy\Models\Coupon', // id
            'Fpaipl\Shopy\Models\Order', // oid

        ])) {

            if (in_array($this->model, [
                'App\Models\User', // uuid
            ])) {
                $query = $query->where('uuid', $request->route()->parameters()[$this->param]);   

            } else if (in_array($this->model, [
                'Fpaipl\Brandy\Models\Party', // sid
                'Fpaipl\Brandy\Models\Employee', // sid
                ])) {
                $query = $query->where('sid', $request->route()->parameters()[$this->param]);   
                         
            } else if (in_array($this->model, [
                'Fpaipl\Shopy\Models\Order', // oid
                ])) {
                $query = $query->where('oid', $request->route()->parameters()[$this->param]);   
                         
            } else {
                $query = $query->where('id', $request->route()->parameters()[$this->param]);
            }
            
        } else {
            $query = $this->model::where('slug', $request->route()->parameters()[$this->param]); // Include trashed results
        }
    
        return $query;
    }
    
    /**
     * Handle 'Method Not Allowed' errors.
     *
     * This method sends an appropriate response when a certain method is not allowed on a model. 
     * The response differs based on whether the request wants a JSON response or not.
     *
     * @param  \Illuminate\Http\Request  $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse 
     * Either a JSON response with an error message, or a redirect back to the previous page with a flash message.
     */
    protected function methodNotAllowed(Request $request)
    {
        if ($request->wantsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Method not allowed'
            ], Response::HTTP_METHOD_NOT_ALLOWED));
        } else {
            session()->flash('toast', [
                'class' => 'warning',
                'text' => $this->messages['method_not_allowed']
            ]);
            return redirect()->back();
        }
    }
}
