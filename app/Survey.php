<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
	protected $fillable = [
        'title',
        'description',
        'share_all'
   ];

   public function user()
   {
        return $this->belongsTo('App\User');
   }

   public function pages()
   {
        return $this->hasMany('App\SurveyPage');
   }


}
