<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->boolean('is_seen')->default(false);
            $table->boolean('deleted_from_sender')->default(false);
            $table->boolean('deleted_from_receiver')->default(false);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('conversation_id')->references('id')->on('conversations');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
