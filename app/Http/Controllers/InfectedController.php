<?php

namespace App\Http\Controllers;

use App\Models\Survivor;
use App\Models\Infected;
use Exception;
use Illuminate\Http\Request;
use DB;

use Laravel\Lumen\Routing\Controller as Controller;

class InfectedController extends Controller
{
    public function postInfected(Request $request, $id) {
        $request_all = $request->all();

        if (($survivor = Survivor::findOrfail($id)) && $id != $request_all['id_survivor_infected']) {
            $survivor = array(
                'id_survivors' => $survivor->id, 
                'id_survivor_infected' => $request_all['id_survivor_infected'], 
            );

            try {

                // TODO
                // we can use firtsOrCreate
                $infected = Infected::updateOrCreate($survivor);

                if (!$setInfect = $this->setInfected($request_all['id_survivor_infected'])) {
                    return $setInfect;
                }
                
                return response()->json([
                    'status' => '1',
                    'message' => 'Created infected with success!',
                    'data' => $infected //->toArray()
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
        } else {
            return response()->json(
                [
                    'status' => '2',
                    'message' => 'Survivor Not Found',
                    'data' => 'Or survivor are reporting him self'
                ],
                400,
                ['X-Header-One' => 'Header Value']
            );
        }
    }

    /**
     * With 3 infected reported, this survivor is marked as infected
     */
    private function setInfected($id_survivor_infected) {
        try {
            $survivor_infected = Infected::where('id_survivor_infected', $id_survivor_infected)
                ->count()
            ;

            if ($survivor_infected >= 3) {
                DB::table('reev.survivors')
                    ->where('id', $id_survivor_infected)
                    ->update(['has_infected' => 1]);
            }

            return true;
        } catch (Exception $e) {
            return response()->json(
                [
                    'status' => '2',
                    'message' => 'Fail to set infected',
                    'data' => $e->getMessage()
                ],
                400,
                ['X-Header-One' => 'Header Value']
            );
        }
    }
}