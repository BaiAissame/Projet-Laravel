<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
   use HasFactory;

   /**
    * Les attributs qui peuvent être assignés en masse.
    *
    * @var array
    */
   protected $fillable = [
      'project_id',
      'email',
      'status',
      'inviter_id',
   ];

   public function project()
   {
      return $this->belongsTo(Project::class);
   }

   public function inviter()
   {
      return $this->belongsTo(User::class, 'inviter_id');
   }
}
