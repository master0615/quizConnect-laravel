<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyElement extends Model
{
	protected $fillable = [
        'type',
        'name'
    ];

    // public function parent()
    // {
    //     return $this->belongsTo('SurveyElement', 'parent_id');
    // }

    // public function elements()
    // {
    //     return $this->hasMany('SurveyElement', 'parent_id');
    // }
}
