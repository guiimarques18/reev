<?php

namespace App\Http\Controllers;

use App\Models\Survivor;
use App\Models\Inventory;
use App\Models\Trade;
use Exception;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as Controller;

class SurvivorsController extends Controller
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
    public function postSurvivor(Request $request, $id) {
        $request_all = $request->all();

        $this->validate($request, [
            'name' => 'required|string|max:65',
            'age' => 'required|numeric',
            'gender' => 'required|string|max:1',
            'location_latitude' => 'required|numeric',
            'location_longitude' => 'required|numeric',
        ]);

        try {
            $survivor = Survivor::create($request_all);

            $request_all['inventory']['id_survivors'] = $survivor->id;

            $storeInventory = $this->storeInventory($request_all['inventory']);

            if ($storeInventory === true) {
                return response()->json([
                    'status' => '1',
                    'message' => 'Created survivor with success!',
                    'data' => $survivor->toArray()
                ]);
            } else {
                return $storeInventory;
            }

        } catch (Exception $e) {
            return response()->json(
                [
                    'status' => '2',
                    'message' => 'Create Error',
                    'data' => $e->getMessage()
                ],
                400,
                ['X-Header-One' => 'Header Value']
            );
        }
    }

    /**
     * Update survivor location
     */
    public function patchSurvivor(Request $request, $id) {
        $request_all = $request->all();

        $survivor = Survivor::findOrfail($id);

        // update only location
        unset($request_all['name']);
        unset($request_all['age']);
        unset($request_all['gender']);

        $this->validate($request, [
            'location_latitude' => 'required|numeric',
            'location_longitude' => 'required|numeric',
        ]);

        try {
            $survivor->update($request_all);

            return response()->json([
                'status' => '1',
                'message' => 'Update survivor with success!',
                'data' => $survivor->toArray()
            ]);
        } catch (Exception $e) {
            return response()->json(
                [
                    'status' => '2',
                    'message' => 'Update Error',
                    'data' => $e->getMessage()
                ],
                400,
                ['X-Header-One' => 'Header Value']
            );
        }
    }

    /**
     * Reports 
     * 
     * 1. Percentage of infected survivors.
     * 2. Percentage of non-infected survivors.
     * 3. Average amount of each kind of resource by survivor (e.g. 5 waters per survivor)
     * 4. Points lost because of infected survivor.
     */
    public function getReports() {
        
        $report = array();

        // 1. Percentage of infected survivors.
        $countSurvivors = Survivor::count();
        $countSurvivorsInfected = Survivor::where('has_infected', 1)->count();
        $percentageInfectedSurvivors = (($countSurvivorsInfected / $countSurvivors) * 100);
        $report['Percentage of infected survivors'] = $percentageInfectedSurvivors;
        
        // 2. Percentage of non-infected survivors.
        $countNonInfected = Survivor::whereNull('has_infected')->count();
        $percentageNonInfectedSurvivors = (($countNonInfected / $countSurvivors) * 100);
        $report['Percentage of non-infected survivors'] = $percentageNonInfectedSurvivors;
        
        // 3. Average amount of each kind of resource by survivor (e.g. 5 waters per survivor)
        $avgPerSurvivor = array();
        $avgPerSurvivor['water'] = Inventory::avg('water');
        $avgPerSurvivor['food'] = Inventory::avg('food');
        $avgPerSurvivor['medication'] = Inventory::avg('medication');
        $avgPerSurvivor['ammunition'] = Inventory::avg('ammunition');
        $report['Average amount of each kind of resource by survivor'] = $avgPerSurvivor;

        // 4. Points lost because of infected survivor
        $trade = new Trade();
        $getSurvivorsInfected = Survivor::select('id')->where('has_infected', 1)->get();
        $pointsLose = 0;
        foreach ($getSurvivorsInfected as $idSurvivorsInfected) {
            // var_dump($idSurvivorsInfected->id);
            $itens = $trade->getItens($idSurvivorsInfected->id);
            $pointsLose += $trade->calculateItensPoints($itens);
        }
        $report['Points lost because of infected survivor'] = $pointsLose;
        

        return $report;
    }

    /**
     * Get Survivor info
     */
    public function getSurvivor (Request $request, $id) {

        try { 
            $survivor = Survivor::findOrfail($id);

            return response()->json(
                [
                    'status' => '1',
                    'message' => 'Sucesso.',
                    'data' => $survivor->toArray()
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'status' => '2',
                    'message' => 'Get Not Found',
                    'data' => $e->getMessage()
                ],
                400,
                ['X-Header-One' => 'Header Value']
            );
        }

    }

    /**
     * Store Inventory
     */
    private function storeInventory($inventory) {
        try {
            $inventory = Inventory::create($inventory);
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
}
