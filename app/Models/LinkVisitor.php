<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'visitor_ip',
        'user_agent',
        'referrer',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }

}
