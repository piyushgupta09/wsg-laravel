<?php

namespace Fpaipl\Shopy\Http\Controllers;

use Illuminate\Http\Request;
use Fpaipl\Shopy\Models\Order;
use Fpaipl\Shopy\DataTables\OrderDatatable as Datatable;
use Fpaipl\Shopy\Actions\CreateOrder;
use Fpaipl\Panel\Http\Controllers\PanelController;
use Fpaipl\Shopy\DataTables\NewOrderDatatable;

class OrderController extends PanelController
{
    public function __construct()
    {
        parent::__construct(
            new Datatable(), 
            'Fpaipl\Shopy\Models\Order', 
            'Order', 'orders.index'
        );
    }

    // /**
    //  * Display a listing of the new resource.
    //  *
    //  * @param  \Fpaipl\Shopy\DataTables\OrderDatatable  $datatable
    //  * @return \Illuminate\Http\Response
    //  */
    // public function newOrders(Request $request)
    // {
    //     if (!Order::INDEXABLE()) {
    //         $this->methodNotAllowed($request);
    //     }

    //     return view('panel::lists.index', [
    //         'model' => get_class(new Order()),
    //         'datatable' => get_class(new NewOrderDatatable()),
    //         'messages' => ['list_page' => 'New Orders'],
    //         'modelName' => 'Order',
    //     ]);
    // }

    // /**
    //  * Display a listing of the processing resource.
    //  *
    //  * @param  \Fpaipl\Shopy\DataTables\OrderDatatable  $datatable
    //  * @return \Illuminate\Http\Response
    //  */
    // public function processingOrders(Request $request)
    // {
    //     if (!Order::INDEXABLE()) {
    //         $this->methodNotAllowed($request);
    //     }

    //     return view('panel::lists.index', [
    //         'model' => get_class(new Order()),
    //         'datatable' => get_class(new Datatable()),
    //         'messages' => ['list_page' => 'Processing Orders'],
    //         'modelName' => 'Order',
    //     ]);
    // }

    /**
     * Create a new order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Fpaipl\Shopy\Actions\CreateOrder  $creator
     * @return \App\Model\Order
     */
    public function store(Request $request, CreateOrder $creator): Order
    {
        // event(new NewOrderCreated(
            $order = $creator->create(auth()->user(), $request->all());
        // ));
        return $order;
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $response = Order::softDeleteModel(
            array($order->id), 
            'App\Models\Order'
        );

        switch ($response) {
            case 'dependent':
                session()->flash('toast', [
                    'class' => 'danger',
                    'text' => $this->messages['has_dependency']
                ]);
                break;
            case 'success':
                session()->flash('toast', [
                    'class' => 'success',
                    'text' => $this->messages['delete_success']
                ]);
                break;    
            default: // failure
                session()->flash('toast', [
                    'class' => 'danger',
                    'text' => $this->messages['delete_error']
                ]);
                break;
        }

        return redirect()->route('orders.index');
    }
}