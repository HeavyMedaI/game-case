<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 * @package App\Models
 *
 * @property integer status
 * @property string position
 * @property string state
 * @property Date created_at
 * @property Date updated_at
 */
class Client extends Model{

    public $table = 'clients';
    public $timestamps = true;

    public $fillable = ['status', 'position', 'state'];
    public $casts = [
        'id' => 'integer',
        'status' => 'string',
        'position' => 'string',
        'state' => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function completedDirectives() {
        return $this->belongsToMany(Directive::class);
    }
}
