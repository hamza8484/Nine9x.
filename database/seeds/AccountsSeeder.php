<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Account;
use App\AccountType;

class AccountsSeeder extends Seeder
{
    public function run()
    {
        $assetsType = AccountType::where('code', 'AST')->first();
        $expensesType = AccountType::where('code', 'EXP')->first();

        if (!$assetsType || !$expensesType) {
            throw new \Exception("تأكد من أن AccountTypesSeeder تم تشغيله بنجاح، وأن الأكواد AST و EXP موجودة.");
        }

        Account::create([
            'name' => 'الأصول الثابتة',
            'code' => '1100',
            'account_type_id' => $assetsType->id,
            'balance' => 0,
            'account_nature' => 'debit',
            'account_group' => 'asset',
            'category' => 'financial',
            'currency_code' => 'SAR',
            'advanced_status' => 'active',
        ]);

        Account::create([
            'name' => 'المصروفات التشغيلية',
            'code' => '2100',
            'account_type_id' => $expensesType->id,
            'balance' => 0,
            'account_nature' => 'debit',
            'account_group' => 'expense',
            'category' => 'expenses',
            'currency_code' => 'SAR',
            'advanced_status' => 'active',
        ]);
    }
}
