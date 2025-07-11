<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            OrganizationSeeder::class,
            OpportunitySeeder::class,
        ]);

        $this->call(Organization1Seeder::class);
        $this->call(ComprehensiveOpportunitySeeder::class);
        $this->call(NewOrganizationsOpportunitiesSeeder::class);
    }
}
