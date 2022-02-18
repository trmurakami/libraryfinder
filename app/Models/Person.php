<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Creative Works associated to this person.
    */
    public function works()
    {
        return $this->belongsToMany(CreativeWork::class, 'creative_work_person');
    }

}
