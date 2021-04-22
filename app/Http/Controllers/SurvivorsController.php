<?php

namespace App\Http\Controllers;

use App\Models\Survivor;
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

            return response()->json([
                'status' => '1',
                'message' => 'Created survivor with success!',
                'data' => $survivor->toArray()
            ]);
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
    
}
