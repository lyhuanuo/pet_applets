<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('admin_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100)->comment('用户名');
            $table->string('email',100)->comment('邮箱');
            $table->char('phone',11)->comment('手机号');
            $table->string('password',100)->comment('密码');//密码字段必须用password
            $table->timestamp('group')->comment('用户组')->nullable();//可以不为空
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('status')->default(0)->comment('状态:0正常');//整型
            $table->ipAddress('ip')->comment('ip地址');
            $table->rememberToken();
//            $table->timestamps();
            $table->integer('ctime');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user');
    }
}
