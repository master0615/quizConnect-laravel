<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyPage extends Model
{
	protected $fillable = [
        'name'
    ];
 
    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }
 
    public function elements()
    {
        return $this->hasMany('App\SurveyElement');
    }
}
