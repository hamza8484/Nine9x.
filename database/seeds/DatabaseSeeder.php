<?php

use Illuminate\Database\Seeder;

use Database\Seeders\BranchSeeder;  // إضافة Seeder الفروع
use Database\Seeders\CompanySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\SupplierSeeder; // إضافة SupplierSeeder
use Database\Seeders\TaxSeeder; // إضافة TaxSeeder
use Database\Seeders\StoreSeeder; // إضافة StoreSeeder
use Database\Seeders\CashboxesSeeder; // إضافة CashboxesSeeder
use Database\Seeders\SubscriptionPlansSeeder;
use Database\Seeders\AccountTypesSeeder;
use Database\Seeders\AccountsSeeder;
use Database\Seeders\FiscalYearsSeeder;
use Database\Seeders\JournalEntriesSeeder;
use Database\Seeders\JournalEntryLinesSeeder;
use Database\Seeders\AccountReconciliationsSeeder;
use Database\Seeders\ReconciliationLineSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BranchSeeder::class,            // استدعاء Seeder الفروع
            CompanySeeder::class,           // استدعاء Seeder الشركات
            CustomerSeeder::class,          // استدعاء Seeder العملاء
            SupplierSeeder::class,          // استدعاء SupplierSeeder
            TaxSeeder::class,               // استدعاء TaxSeeder
            StoreSeeder::class,             // استدعاء StoreSeeder
            CashboxesSeeder::class,         // استدعاء CashboxesSeeder
            SubscriptionPlansSeeder::class, // استدعاء Seeder خطط الاشتراك
            AccountTypesSeeder::class,
            AccountsSeeder::class,
            FiscalYearsSeeder::class,
            JournalEntriesSeeder::class,
            JournalEntryLinesSeeder::class,
            AccountReconciliationsSeeder::class,
            ReconciliationLineSeeder::class,
        ]);
    }
}
