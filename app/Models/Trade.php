<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{

    // protected $primaryKey = 'id';

    // protected $dateFormat = 'Y-m-d H:i:sO';

    // public $incrementing = true;

    // protected $table = 'reev.';
    
    /**
     * Attributes
     *
     * @var array
     */
    protected $fillable = [
       '',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

    /**
     * Calculate points of list itens
     * 
     * return @numberOfPoints
     */
    public function calculateItensPoints($itens) {
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

    /**
     * Search itens of Survivor to tradeOf
     * 
     * return @listItens
     */
    public function getItens($id) {
        $itens = Inventory::where('id_survivors', $id)->get()->toArray();
        $itens = $itens[0];

        $itens['inventory']['water'] = $itens['water'];
        $itens['inventory']['food'] = $itens['food'];
        $itens['inventory']['medication'] = $itens['medication'];
        $itens['inventory']['ammunition'] = $itens['ammunition'];

        return $itens['inventory'];
    }
}
