<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedinteger('parent_id');
            $table->unsignedinteger('node_index');
            $table->timestamps();
        });


        DB::unprepared('DELIMITER $$ CREATE PROCEDURE updateNodeIndex( IN categoryId INT(10), IN parentId INT(11), IN currentParent INT(11),
                IN nodeIndex INT(11), IN currentIndex INT(11), IN isNewNode BOOLEAN, IN isDeletionOperation BOOLEAN)
            BEGIN
                UPDATE
                    categories
                SET
                    node_index = CASE
                        WHEN isDeletionOperation = true AND parent_id = currentParent AND node_index > currentIndex THEN node_index - 1
                        WHEN isNewNode = true AND parent_id = currentParent AND node_index > currentIndex THEN node_index - 1
                        WHEN isNewNode = true AND parent_id = parentId AND node_index >= nodeIndex AND id != categoryId THEN node_index + 1
                        WHEN isNewNode = false AND isDeletionOperation = false AND parent_id = parentId AND node_index < currentIndex AND node_index >= nodeIndex THEN node_index + 1
                        WHEN isNewNode = false AND isDeletionOperation = false AND parent_id = parentId AND node_index > currentIndex AND node_index <= nodeIndex THEN node_index - 1
                        ELSE node_index
                        END;
            END'
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');

        DB::unprepared('DROP PROCEDURE IF EXISTS updateNodeIndex');
    }
}
