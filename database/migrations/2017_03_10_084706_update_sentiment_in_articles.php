<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSentimentInArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->decimal('score', 3, 2)->nullable()->change();
            $table->decimal('magnitude', 4, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->decimal('score', 3, 2)->nullable(false)->change();
            $table->decimal('magnitude', 4, 2)->nullable(false)->change();
        });
    }
}
