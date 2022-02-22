<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreativeWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'countryOfOrigin',
        'datePublished',
        'doi',
        'inLanguage',
        'name',
        'type',
        'type_schema_org',
        'url'
    ];

    /**
     * Creative Works associated to this person.
    */
    public function authors()
    {
        return $this->belongsToMany(Person::class, 'creative_work_person');
    }

}
