<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareListing extends Model
{
    protected $guarded = [];

    public function seller()  { return $this->belongsTo(User::class, 'seller_id'); }
    public function project() { return $this->belongsTo(Project::class); }
    public function trades()  { return $this->hasMany(ShareTrade::class, 'listing_id'); }
}