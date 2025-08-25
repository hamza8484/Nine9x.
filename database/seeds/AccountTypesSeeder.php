<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\AccountType;


class AccountTypesSeeder extends Seeder
{
   public function run(): void
    {
        $types = [
            ['name' => 'أصول', 'code' => 'AST', 'description' => 'الحسابات المرتبطة بالأصول', 'is_active' => true],
            ['name' => 'خصوم', 'code' => 'LIA', 'description' => 'الحسابات المرتبطة بالخصوم', 'is_active' => true],
            ['name' => 'حقوق ملكية', 'code' => 'EQY', 'description' => 'حقوق الملاك', 'is_active' => true],
            ['name' => 'إيرادات', 'code' => 'REV', 'description' => 'حسابات الإيرادات', 'is_active' => true],
            ['name' => 'مصاريف', 'code' => 'EXP', 'description' => 'حسابات المصاريف', 'is_active' => true],
        ];

        // استخدام Eloquent لإنشاء السجلات في جدول account_types
        foreach ($types as $type) {
            AccountType::create($type);
        }
    }
}

