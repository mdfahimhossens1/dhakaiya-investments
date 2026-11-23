<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareTrade extends Model
{
    protected $guarded = [];

    public function listing() { return $this->belongsTo(ShareListing::class, 'listing_id'); }
    public function project() { return $this->belongsTo(Project::class); }
    public function seller()  { return $this->belongsTo(User::class, 'seller_id'); }
    public function buyer()   { return $this->belongsTo(User::class, 'buyer_id'); }
}