<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Sensor extends Model  {
    protected $primaryKey = 'unique_id';
    protected $table = 'sensors';


    public function sensorconfig()
    {
        return $this->hasOne('App\SensorConfig', 'sensors_unique_id', 'unique_id');
    }

    public function datalog()
    {
        return $this->hasMany('App\Datalog', 'sensors_unique_id', 'unique_id');
    }

    public function mapentry()
    {
        return $this->hasMany('App\MapEntry', 'sensors_unique_id', 'unique_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'users_id', 'id');
    }

    public function delete()
    {
        $this->sensorconfig()->delete();
        $this->datalog()->delete();
        $this->mapentry()->delete();
        return parent::delete();
    }
}
