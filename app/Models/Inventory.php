<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d H:i:sO';

    public $incrementing = true;

    protected $table = 'reev.inventory';
    
    /**
     * Attributes
     *
     * @var array
     */
    protected $fillable = [
       'id', 'id_survivors', 'water', 'food', 'medication', 'ammunition',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];
}
