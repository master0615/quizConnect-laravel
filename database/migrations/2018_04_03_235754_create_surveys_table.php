<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer( 'user_id' )->unsigned();
            $table->foreign('user_id')->references( 'id' )->on( 'users' );

            $table->boolean( 'share_all' )->default(false);   
            $table->string( 'title' );
            $table->string( 'description' )->nullable()->default('');
            $table->text( 'locale' )->nullable();

            $table->boolean( 'focusFirstQuestionAutomatic' )->nullable();  
            $table->text( 'completedHtml' )->nullable();
            $table->text( 'completedBeforeHtml' )->nullable();
            $table->text( 'loadingHtml' )->nullable();
            $table->string( 'cookieName' )->nullable();
            $table->boolean( 'sendResultOnPageNext' )->nullable();
            $table->boolean( 'showNavigationButtons' )->nullable();
            $table->boolean( 'showPrevButton' )->nullable();
            $table->boolean( 'showTitle' )->nullable();
            $table->boolean( 'showPageTitles' )->nullable();
            $table->boolean( 'showCompletedPage' )->nullable();
            $table->enum( 'questionsOrder', ['random', 'initial'] )->nullable();
            $table->boolean( 'showPageNumbers' )->nullable();
            $table->enum( 'showQuestionNumbers', ['off', 'onSurvey', 'onPage'] )->nullable();
            $table->enum( 'questionTitleLocation', ['top', 'bottom', 'left'] )->nullable();
            $table->enum( 'questionErrorLocation', ['top', 'bottom'] )->nullable();
            $table->enum( 'showProgressBar', ['off', 'top', 'bottom'] )->nullable();
            $table->enum( 'mode',['display', 'edit'] )->nullable();  
            $table->boolean( 'storeOthersAsComment' )->nullable();
            $table->integer( 'maxTextLength' )->unsigned()->nullable();
            $table->integer( 'maxOthersLength' )->unsigned()->nullable();
            $table->enum( 'goNextPageAutomatic',[true, false, 'autogonext'] )->nullable();
            $table->enum( 'clearInvisibleValues', ['none', 'onComplete', 'onHidden'] )->nullable();
            $table->enum( 'checkErrorsMode', ['onNextPage', 'onValueChanged'] )->nullable();   
            $table->text( 'startSurveyText' )->nullable();
            $table->text( 'pagePrevText' )->nullable();
            $table->text( 'pageNextText' )->nullable();
            $table->text( 'completeText' )->nullable();
            $table->string( 'requiredText' )->nullable();
            $table->string( 'questionStartIndex' )->nullable();
            $table->text( 'questionTitleTemplate' )->nullable();
            $table->boolean( 'firstPageIsStarted' )->nullable();  
            $table->boolean( 'isSinglePage' )->nullable();
            $table->integer( 'maxTimeToFinish' )->unsigned()->nullable();
            $table->integer( 'maxTimeToFinishPage' )->unsigned()->nullable();
            $table->enum( 'showTimerPanel', ['none', 'top', 'bottom'] )->nullable();
            $table->enum( 'showTimerPanelMode', ['all', 'page', 'survey'] )->nullable();
            $table->text( 'triggers' )->nullable();

            // $table->integer( 'pageCount' )->unsigned();
            // $table->string( 'commentPrefix' )->nullable();
            // $table->text( 'onServerValidateQuestions' )->nullable();
            // $table->string( 'surveyId' )->nullable();
            // $table->string( 'surveyPostId' )->nullable();
            // $table->string( 'surveyShowDataSaving' )->nullable();
            // $table->integer( 'timeSpent' )->unsigned()->nullable();

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
        Schema::dropIfExists('surveys');
    }
}
