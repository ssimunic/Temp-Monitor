<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class MapEntry extends Model  {
    protected $primaryKey = 'id';
    protected $table = 'maps_entry';
    public $timestamps = false;

    public function sensor()
    {
        return $this->hasOne('App\Sensor', 'unique_id', 'sensors_unique_id');
    }

    public function map()
    {
        return $this->belongsTo('App\Map', 'maps_id', 'id');
    }
}
