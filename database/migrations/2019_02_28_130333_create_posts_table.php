<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('profile_id');
            $table->string('ig_post_id');
            $table->text('caption')->nullable();
            $table->string('type');
            $table->string('post_url', 500);
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
