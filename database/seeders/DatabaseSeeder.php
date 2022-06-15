<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Employee;
use App\Models\AccessCard;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //create departments
        DB::table('departments')->insert([
            ['name' => 'Development', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HR', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sales', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Headquarters', 'created_at' => now(), 'updated_at' => now()]
        ]);
        DB::table('buildings')->insert([
            ['name' => 'Isaac Newton', 'country' => 'UK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oscar Wilde', 'country' => 'UK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Charles Darwin', 'country' => 'UK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Benjamin Frankli', 'country' => 'USA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Luciano Pavarotti', 'country' => 'Italy', 'created_at' => now(), 'updated_at' => now()]
        ]);

        $building = Building::where('name','Isaac Newton')->first()->departments()->attach(Department::where('name','Development')->first()->id);
        $building = Building::where('name','Isaac Newton')->first()->departments()->attach(Department::where('name','Accounting')->first()->id);
        $building = Building::where('name','Oscar Wilde')->first()->departments()->attach(Department::where('name','HR')->first()->id);
        $building = Building::where('name','Oscar Wilde')->first()->departments()->attach(Department::where('name','Sales')->first()->id);
        $building = Building::where('name','Charles Darwin')->first()->departments()->attach(Department::where('name','Headquarters')->first()->id);
        $building = Building::where('name','Benjamin Frankli')->first()->departments()->attach(Department::where('name','Development')->first()->id);
        $building = Building::where('name','Benjamin Frankli')->first()->departments()->attach(Department::where('name','Sales')->first()->id);
        $building = Building::where('name','Luciano Pavarotti')->first()->departments()->attach(Department::where('name','Development')->first()->id);
        $building = Building::where('name','Luciano Pavarotti')->first()->departments()->attach(Department::where('name','Sales')->first()->id);

        // Building::factory()->count(5)->create()->each(
        //     function ($building) {
        //         $departments = Department::inRandomOrder()->take(2)->get();
        //         $building->departments()->attach($departments[0]->id);
        //         $building->departments()->attach($departments[1]->id);
        //     }
        // );
        Employee::factory()->count(50)->create()->each(
            function ($employee) {
                $accessCard = AccessCard::factory()->make();
                $employee->accessCard()->save($accessCard);

                //get 2 random departments and attach to department-employee relationship
                $departments = Department::inRandomOrder()->take(2)->get();
                $employee->departments()->attach($departments[0]->id);
                $employee->departments()->attach($departments[1]->id);
            }
        );

    }
}
