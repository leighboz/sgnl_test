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
        // create departments
        DB::table('departments')->insert([
            ['name' => 'Accounting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Development', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Director', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Headquarters', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'HR', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sales', 'created_at' => now(), 'updated_at' => now()]            
        ]);

        // create buildings
        DB::table('buildings')->insert([
            ['name' => 'Isaac Newton', 'country' => 'UK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oscar Wilde', 'country' => 'UK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Charles Darwin', 'country' => 'UK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Benjamin Frankli', 'country' => 'USA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Luciano Pavarotti', 'country' => 'Italy', 'created_at' => now(), 'updated_at' => now()]
        ]);

        // set building-department relationships
        $building = Building::where('name','Isaac Newton')->first()->departments()->attach(Department::where('name','Development')->first()->id);
        $building = Building::where('name','Isaac Newton')->first()->departments()->attach(Department::where('name','Accounting')->first()->id);
        $building = Building::where('name','Oscar Wilde')->first()->departments()->attach(Department::where('name','HR')->first()->id);
        $building = Building::where('name','Oscar Wilde')->first()->departments()->attach(Department::where('name','Sales')->first()->id);
        $building = Building::where('name','Charles Darwin')->first()->departments()->attach(Department::where('name','Headquarters')->first()->id);
        $building = Building::where('name','Benjamin Frankli')->first()->departments()->attach(Department::where('name','Development')->first()->id);
        $building = Building::where('name','Benjamin Frankli')->first()->departments()->attach(Department::where('name','Sales')->first()->id);
        $building = Building::where('name','Luciano Pavarotti')->first()->departments()->attach(Department::where('name','Development')->first()->id);
        $building = Building::where('name','Luciano Pavarotti')->first()->departments()->attach(Department::where('name','Sales')->first()->id);

        // create test data
        $test_employee = new Employee;
        $test_employee->given_names = 'Julius';
        $test_employee->family_name = 'Caesar';
        $test_employee->date_of_birth = '0100-07-12';
        $test_employee->gender = 'male';
        $test_employee->save();
        $test_access_card = new AccessCard;
        $test_access_card->rfid = '142594708f3a5a3ac2980914a0fc954f';
        $test_employee->accessCard()->save($test_access_card);
        $test_employee->departments()->attach(Department::where('name','Director')->first()->id);
        $test_employee->departments()->attach(Department::where('name','Development')->first()->id);

        // create 50 employees and access cards then set (employee-access card) and (employee-department) relationships
        Employee::factory()->count(50)->create()->each(
            function ($employee) {
                $access_card = AccessCard::factory()->make();
                $employee->accessCard()->save($access_card);

                //get 2 random departments and attach to department-employee relationship
                $departments = Department::inRandomOrder()->take(2)->get();
                $employee->departments()->attach($departments[0]->id);
                $employee->departments()->attach($departments[1]->id);
            }
        );
    }
}
