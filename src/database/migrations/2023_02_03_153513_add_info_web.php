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
        Schema::create('web_info', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("state")->nullable();
            $table->string("city")->nullable();
            $table->string("street")->nullable();
            $table->string("no")->nullable();
            $table->string("zip")->nullable();
            $table->string("email")->nullable();
            $table->boolean("blog")->default(true);
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
        Schema::dropIfExists('web_info');
    }
};
