<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_pages', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer( 'survey_id' )->unsigned();
            $table->foreign('survey_id')->references( 'id' )->on( 'surveys' );

            $table->string( 'name' );
            $table->boolean( 'visible' )->nullable();   
            $table->text( 'visibleIf' )->nullable();
            $table->enum( 'questionTitleLocation', ['top', 'bottom', 'left'] )->nullable();
            $table->string( 'title' )->nullable();
            $table->string( 'description' )->nullable();
            $table->enum( 'navigationButtonsVisibility',['inherit', 'show', 'hide'] )->nullable(); 
            $table->enum( 'questionsOrder', ['random', 'initial'] )->nullable();
            $table->integer( 'maxTimeToFinish' )->unsigned()->nullable();   

            // $table->boolean( 'isRequired' )->nullable();   
            // $table->string( 'popupdescription' )->nullable();
            // $table->text( 'requiredErrorText' )->nullable();
            // $table->integer( 'timeSpent' )->unsigned()->nullable();
            // $table->integer( 'visibleIndex' )->nullable();
            
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
        Schema::dropIfExists('survey_pages');
    }
}
