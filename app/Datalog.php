<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Datalog extends Model  {
    protected $primaryKey = 'id';
    protected $table = 'datalog';
    public $timestamps = false;

    public function sensor()
    {
        return $this->belongsTo('App\Sensor', 'sensors_unique_id', 'unique_id');
    }
}
