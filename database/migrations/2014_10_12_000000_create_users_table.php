<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('用户名');
            $table->tinyInteger('is_admin')->default(2)->comment('是否超管:1-是;-2-否');
            $table->tinyInteger('is_block')->default(2)->comment('是否禁用:1-是;-2-否');
            $table->string('email')->unique()->length(100)->comment('账户');
            $table->string('password')->comment('密码');
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
        Schema::dropIfExists('users');
    }
}
