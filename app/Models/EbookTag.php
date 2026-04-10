<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbookTag extends Model
{
    protected $fillable = ['ebook_id', 'tag_name'];

    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }
}
