<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_profile_permissions',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_profile_id');
                $table->foreign('user_profile_id')->references('id')->on('user_profiles');
                $table->uuid('permission_id');
                $table->foreign('permission_id')->references('id')->on('permissions');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile_permissions');
    }
}
