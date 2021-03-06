<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'can_manage_users',
      'can_manage_departments',
      'can_manage_stamps',
      'can_manage_projects',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $cast = [
        'can_manage_users' => 'boolean',
        'can_manage_departments' => 'boolean',
        'can_manage_stamps' => 'boolean',
        'can_manage_projects' => 'boolean',

    ];


    public function user(){
      return $this->hasMany(User::class);
    }

}
