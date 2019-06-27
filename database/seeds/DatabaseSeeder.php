<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call(BloodGroupSeeder::class);
    $this->call(CountrySeeder::class);
    $this->call(GenderSeeder::class);
    $this->call(GenotypeSeeder::class);
    $this->call(BankSeeder::class);
    $this->call(MaritalStatusSeeder::class);
    $this->call(TitleSeeder::class);
    $this->call(StateSeeder::class);
    $this->call(LgaSeeder::class);
    $this->call(TownSeeder::class);
    $this->call(PermissionSeeder::class);
    $this->call(HcpTypeSeeder::class);

    $this->call(AgencyPermissionSeeder::class);
    $this->call(HcpPermissionSeeder::class);
    $this->call(InstitutionPermissionSeeder::class);
    $this->call(HcpSeeder::class);
    $this->call(InstitutionSeeder::class);
    $this->call(UserSeeder::class);
  }
}
