<?php

namespace App\Http\Controllers;

use App\Models\Survivor;
use App\Models\Inventory;
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

        $pointsToTradeOffer = $this->calculateItensPoints($itensToTradeOffer);
        $pointsToTradeReceiving = $this->calculateItensPoints($itensToTradeReceiving);

        if ($pointsToTradeOffer == $pointsToTradeReceiving) {

            // add
            $itens = $this->getItens($request_all['trade_with']);
            $sums = array();
            foreach (array_keys($itensToTradeReceiving + $itens) as $key) {
                $sums[$key] = (isset($itensToTradeReceiving[$key]) ? $itensToTradeReceiving[$key] : 0) + (isset($itens[$key]) ? $itens[$key] : 0);
            }

            // rem
            $itens = $this->getItens($id);
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

    /**
     * Search itens of Survivor to tradeOf
     * 
     * return @listItens
     */
    private function getItens($id) {
        $itens = Inventory::where('id_survivors', $id)->get()->toArray();
        $itens = $itens[0];

        $itens['inventory']['water'] = $itens['water'];
        $itens['inventory']['food'] = $itens['food'];
        $itens['inventory']['medication'] = $itens['medication'];
        $itens['inventory']['ammunition'] = $itens['ammunition'];

        return $itens['inventory'];
    }

    /**
     * Calculate points of list itens
     * 
     * return @numberOfPoints
     */
    private function calculateItensPoints($itens) {
        $pointsBase = array(
            'water' => 4,
            'food' => 3,
            'medication' => 2,
            'ammunition' => 1,
        );

        return $pointsBase['water'] * $itens['water']
                + $pointsBase['food'] * $itens['food']
                + $pointsBase['medication'] * $itens['medication']
                + $pointsBase['ammunition'] * $itens['ammunition']
            ;

    }
}