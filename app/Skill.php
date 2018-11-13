<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
  protected $fillable = ['name_skill', 'level', 'worker_id'];
}
