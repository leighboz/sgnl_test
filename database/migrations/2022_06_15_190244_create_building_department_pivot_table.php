<?php

use App\Models\building;
use App\Models\department;
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
        Schema::create('building_department', function (Blueprint $table) {
            $table->foreignIdFor(building::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(department::class)->constrained()->onDelete('cascade');
            $table->primary(['building_id', 'department_id']);

            $table->index('building_id');
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_department');
    }
};
