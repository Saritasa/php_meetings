<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSynonymsSavedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('words', function (Blueprint $table) {
            $table->boolean('synonyms_saved')->default(false);
        });

        DB::statement('UPDATE words SET synonyms_saved=1 WHERE EXISTS(SELECT id FROM synonyms WHERE synonyms.word_id=words.id)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('words', function (Blueprint $table) {
            $table->dropColumn('synonyms_saved');
        });
    }
}
