<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events;

class Ticket extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'lane_id', 'position'];

    public function lane()
    {
        return $this->belongsTo(Lane::class);
    }
    protected $dispatchesEvents = [
        'moved' => Events\TicketMoved::class,
    ];
}
