<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('beacon_setups', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('status')->default('pending');
            $table->string('api_key')->nullable();
            $table->string('user_key')->nullable();
            $table->timestamps();
        });

        Schema::table('beacons', function (Blueprint $table){
            $table->string('bt_address')->nullable();
            $table->timestamp('last_heartbeat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beacon_setups');
        Schema::table('beacons', function (Blueprint $table){
            $table->dropColumn('bt_address');
            $table->dropColumn('last_heartbeat');
        });
    }
};
