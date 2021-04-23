<?php

namespace App\Http\Controllers;

use App\Models\Survivor;
use App\Models\Inventory;
use App\Models\Trade;
use Exception;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as Controller;

class TradeController extends Controller
{
     /**
     * Create a new controller instance.
     * With Authtentication, if necessary
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Add survivors to the database
     */
    public function postTrade(Request $request, $id) {
        $request_all = $request->all();

        // TODO
        // $this->checkItensToTrade($id);

        $itensToTradeOffer = $request_all['offer'];
        $itensToTradeReceiving = $request_all['receiving'];

        $trade = new Trade();

        $pointsToTradeOffer = $trade->calculateItensPoints($itensToTradeOffer);
        $pointsToTradeReceiving = $trade->calculateItensPoints($itensToTradeReceiving);

        if ($pointsToTradeOffer == $pointsToTradeReceiving) {

            // add
            $itens = $trade->getItens($request_all['trade_with']);
            $sums = array();
            foreach (array_keys($itensToTradeReceiving + $itens) as $key) {
                $sums[$key] = (isset($itensToTradeReceiving[$key]) ? $itensToTradeReceiving[$key] : 0) + (isset($itens[$key]) ? $itens[$key] : 0);
            }

            // rem
            $itens = $trade->getItens($id);
            $subs = array();
            foreach (array_keys($itensToTradeOffer + $itens) as $key) {
                $subs[$key] = (isset($itens[$key]) ? $itens[$key] : 0) - (isset($itensToTradeOffer[$key]) ? $itensToTradeOffer[$key] : 0);
            }

            // TODO
            // check if no negative trade

            try {
                $inventoryOffer = Inventory::where('id_survivors', $id);
                $inventoryOffer->update($subs);


                $inventoryReceiving = Inventory::where('id_survivors', $request_all['trade_with']);
                $inventoryReceiving->update($sums);

                return response()->json([
                    'status' => '1',
                    'message' => 'Update survivor with success!',
                    'data' => '',
                ]);
            } catch (Exception $e) {
                return response()->json(
                    [
                        'status' => '2',
                        'message' => 'Update Inventory Error',
                        'data' => $e->getMessage()
                    ],
                    400,
                    ['X-Header-One' => 'Header Value']
                );
            }

        } else {
            return response()->json([
                'status' => '2',
                'message' => 'Points Different!',
                'data' => ''
            ]);
        }
    }
    
}