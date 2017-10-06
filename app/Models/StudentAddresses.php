<?php
/**
 * Created by PhpStorm.
 * User: Serega2
 * Date: 07.10.2017
 * Time: 0:43
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class StudentAddresses extends Model
{
    protected $table = 'student_address';

    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo(Students::class, 'address_id', 'id');
    }
}