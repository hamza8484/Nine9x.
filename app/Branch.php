<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\User;

class Branch extends Model
{
   

    protected $fillable = [
        'bra_name', 
        'bra_type', 
        'bra_address', 
        'bra_phone', 
        'bra_manager', 
        'bra_manager_phone', 
        'is_active', 
        'user_id', // تأكد من وجود user_id فقط هنا
        'bra_telephon', 
        'branch_notes',
    ];
    
    // في نموذج Branch
    public function stores()
    {
        return $this->hasMany(Store::class); // العلاقة مع المخازن
    }

    // علاقة مع المستخدم (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
