<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDetailUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_user', function (Blueprint $table) {
            //FOREIGN KEY Start
            // Foreign key user_id
            $table->foreign('user_id', 'fk_detail_user_to_users')
            ->references('id')
            ->on('users')
            ->onDelete('CASCADE')
            ->onUpdate('CASCADE');

            // foreign key type_user_id
            $table->foreign('type_user_id', 'fk_detail_user_to_type_user')
            ->references('id')
            ->on('type_user')
            ->onDelete('CASCADE')
            ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_user',
        function (Blueprint $table) {
            //Daftar foreign key
            $table->dropForeign('fk_detail_user_to_users');
            $table->dropForeign('fk_detail_user_to_type_user');
        });
    }
}
