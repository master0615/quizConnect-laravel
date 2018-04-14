<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfilePhoto extends Model
{
    protected $fillable = [
        'user_id', 
        'name',
        'ext'
   ];

   public function user()
   {
        $this->belongsTo('App\User');
   }
   
   public function path()
   {
       return action('StorageController@getFile', ['profile_photo', $this->id, $this->ext]);
   }

   public function thumb()
   {
       return action('StorageController@getFile', ['profile_photo', $this->id, $this->ext, 1]);
   }
}
