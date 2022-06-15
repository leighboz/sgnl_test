<?php

use App\Models\department;
use App\Models\employee;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_employee', function (Blueprint $table) {
            $table->foreignIdFor(department::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(employee::class)->constrained()->onDelete('cascade');
            $table->primary(['department_id', 'employee_id']);

            $table->index('department_id');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_employee');
    }
};
