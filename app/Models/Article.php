<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class article extends Model
{
    use SoftDeletes;

    use HasFactory;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function setSlugAttribute($value) {

        if (article::whereSlug($slug = str_slug($value))->exists()) {

            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug) {

        $original = $slug;

        $count = 2;

        while (article::whereSlug($slug)->exists()) {

            $slug = "{$original}-" . $count++;
        }

        return $slug;
    }

    protected $dates = ['deleted_at'];

    public $fillable = [
        'title',
        'slug',
        'content',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'content' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'content' => 'required',
    ];

}
