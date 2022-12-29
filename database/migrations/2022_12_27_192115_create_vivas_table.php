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
            $table->double('sup_mark', 8, 2);
            $table->double('pre_mark', 8, 2);
            $table->double('exa_mark', 8, 2);
            $table->string('sup_name');
            $table->string('pre_name');
            $table->string('exa_name');
            $table->double('final_mark', 8, 2);
            $table->string('code')->unique();
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
