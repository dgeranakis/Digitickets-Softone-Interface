<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Domain extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['description'];
    protected $fillable = ['code', 'created_by_user', 'updated_by_user'];

    /**
     * Get the enumerations for the domain.
     */
    public function enumerations()
    {
        return $this->hasMany(Enumeration::class);
    }
}
