<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Organization::create([
            'name' => 'منظمة البيئة',
            'location' => 'الدمام',
            'description' => 'منظمة تعمل في مجال البيئة',
        ]);

        Organization::create([
            'name' => 'منظمة الصحة',
            'location' => 'جدة',
            'description' => 'منظمة تعمل في المجال الصحي',
        ]);

        Organization::create([
            'name' => 'منظمة التعليم',
            'location' => 'الرياض',
            'description' => 'منظمة تهتم بتطوير التعليم',
        ]);
    }
}
