<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Map extends Model  {
    protected $primaryKey = 'id';
    protected $table = 'maps';


    public function mapentry()
    {
        return $this->hasMany('App\MapEntry', 'maps_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'users_id', 'id');
    }

    public function delete()
    {
        $this->mapentry()->delete();
        return parent::delete();
    }
}
