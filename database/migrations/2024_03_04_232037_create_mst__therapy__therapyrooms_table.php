<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstTherapyTherapyroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst__therapy__therapyrooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('therapy_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('therapy_room_id');
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
        Schema::dropIfExists('mst__therapy__therapyrooms');
    }
}
