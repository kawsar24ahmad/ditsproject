<?php

namespace App\Models;

use App\Models\ServiceTask;
use App\Models\ServiceAssign;
use Illuminate\Cache\Events\CacheEvent;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'offer_price',
        'category_id',
        'thumbnail',
        'is_active',
        'icon',
        'type',
        'form_fields_json',
        'view_path',
        'external_link'
    ];


    public function category()  {
        return $this->belongsTo(Category::class);
    }
    public function tasks()
    {
        return $this->hasMany(ServiceTask::class);
    }
    public function serviceAssign()
    {
        return $this->hasMany(ServiceAssign::class);
    }


}
