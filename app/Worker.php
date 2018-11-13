<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
  protected $fillable = ['name', 'email', 'job', 'birthdate', 'residence'];

  // Has Many skills
  public function Skills()
  {
    return $this->hasMany('App\Skill');
  }
}
