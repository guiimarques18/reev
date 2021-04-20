<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infected extends Model
{

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d H:i:sO';

    public $incrementing = true;

    protected $table = 'reev.infected';
    
    /**
     * Attributes
     *
     * @var array
     */
    protected $fillable = [
       'id', 'id_survivors', 'id_survivor_infected',
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
