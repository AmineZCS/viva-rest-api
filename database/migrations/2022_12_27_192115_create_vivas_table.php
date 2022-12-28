<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vivas', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->year('year');
            $table->float('sup_mark', 3, 2);
            $table->float('pre_mark', 3, 2);
            $table->float('exa_mark', 3, 2);
            $table->string('sup_name');
            $table->string('pre_name');
            $table->string('exa_name');
            $table->float('final_mark', 3, 2);
            $table->string('code');
            $table->json('students'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vivas');
    }
};
