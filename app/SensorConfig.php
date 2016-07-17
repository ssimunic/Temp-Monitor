<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class SensorConfig extends Model  {
    protected $primaryKey = 'id';
    protected $table = 'sensors_config';

    public function sensor()
    {
        return $this->belongsTo('App\Sensor', 'sensors_unique_id', 'unique_id');
    }
}
