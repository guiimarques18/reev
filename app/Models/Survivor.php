<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survivor extends Model
{

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d H:i:sO';

    public $incrementing = true;

    protected $table = 'reev.survivors';
    
    /**
     * Attributes
     *
     * @var array
     */
    protected $fillable = [
       'id', 'name', 'age', 'gender', 'location_latitude', 'location_longitude',
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
