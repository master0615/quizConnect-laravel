<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_elements', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer( 'survey_page_id' )->unsigned();
            $table->foreign('survey_page_id')->references( 'id' )->on( 'survey_pages' );

            $table->integer( 'parent_id' )->unsigned()->nullable();

            $table->enum( 'type',['text', 'checkbox', 'radiogroup', 'dropdown', 'comment', 'rating', 'boolean', 'html', 'expression', 'file', 'matrix', 'matrixdropdown', 'matrixdynamic','multipletext', 'panel', 'paneldynamic', 'tagbox', 'datepicker', 'barrating', 'sortablelist', 'imagepicker', 'nouislider', 'signaturepad', 'editor', 'autocomplete1', 'bootstrapslider'] );
            $table->string( 'name' );

            $table->boolean( 'visible' )->nullable();   
            $table->text( 'visibleIf' )->nullable();
            $table->integer( 'width' )->nullable();
            $table->boolean( 'startWithNewLine' )->nullable(); 
            $table->integer( 'indent' )->nullable();
            $table->text( 'title' )->nullable();
            $table->text( 'description' )->nullable();
            $table->text( 'commentText' )->nullable();
            $table->text( 'valueName' )->nullable();
            $table->text( 'enableIf' )->nullable();
            $table->text( 'defaultValue' )->nullable();
            $table->text( 'correctAnswer' )->nullable();
            $table->boolean( 'isRequired' )->nullable();   
            $table->text( 'requiredErrorText' )->nullable();
            $table->boolean( 'readOnly' )->nullable(); 
            $table->text( 'validators' )->nullable();

            $table->boolean( 'hasOther' )->nullable(); 
            $table->text( 'choices' )->nullable();
            $table->enum( 'choicesOrder', ['none', 'ascending', 'descending', 'random'] )->nullable();
            $table->text( 'choicesByUrl' )->nullable();
            $table->text( 'otherText' )->nullable();
            $table->text( 'otherErrorText' )->nullable();
            $table->boolean( 'storeOthersAsComment' )->nullable(); 

            $table->string( 'renderAs' )->nullable();

            $table->enum( 'titleLocation', ['top', 'bottom', 'left'] )->nullable();
            $table->enum( 'questionTitleLocation', ['top', 'bottom', 'left'] )->nullable();
            $table->enum( 'inputType',['color', 'date', 'datetime', 'datetime-local', 'email', 'month', 'number', 'password', 'range', 'tel', 'text', 'time', 'url', 'week'] )->nullable();  
            $table->bigInteger( 'size' )->nullable();
            $table->integer( 'maxLength' )->nullable();
            $table->text( 'placeHolder' )->nullable();
            $table->string( 'inputFormat' )->nullable();
            $table->enum( 'inputMask', ['none', 'datetime', 'currency', 'decimal', 'email', 'phone', 'ip'] )->nullable();

            $table->boolean( 'hasComment' )->nullable(); 
            $table->boolean( 'showLabel' )->nullable(); 

            $table->boolean( 'showValues' )->nullable();
            $table->enum( 'ratingTheme', ['fontawesome-stars', 'css-stars', 'bars-pill', 'bars-1to10', 'bars-movie', 'bars-square', 'bars-reversed', 'bars-horizontal', 'bootstrap-stars', 'fontawesome-stars-o'] )->nullable();


            $table->text( 'cols' )->nullable();
            $table->text( 'rows' )->nullable();

            $table->text( 'rateValues' )->nullable();
            $table->integer( 'rateMin' )->nullable();
            $table->integer( 'rateMax' )->nullable();
            $table->integer( 'rateStep' )->nullable();
            $table->text( 'maxRateDescription' )->nullable();
            $table->text( 'minRateDescription' )->nullable();

            $table->boolean( 'showTitle' )->nullable();  
            $table->text( 'label' )->nullable();
            $table->string( 'valueFalse' )->nullable();
            $table->string( 'valueTrue' )->nullable();

            $table->text( 'html' )->nullable();

            $table->string( 'expression' )->nullable();
            $table->text( 'format' )->nullable();
            $table->text( 'displayStyle' )->nullable();
            $table->string( 'currency' )->nullable();
            $table->boolean( 'useGrouping' )->nullable();   

            $table->boolean( 'showPreview' )->nullable(); 
            $table->boolean( 'allowMultiple' )->nullable();   
            $table->integer( 'imageHeight' )->nullable();
            $table->integer( 'imageWidth' )->nullable();
            $table->bigInteger( 'maxSize' )->nullable();
            $table->boolean( 'storeDataAsText' )->nullable();  

            $table->text( 'columns' )->nullable();
            $table->text( 'cells' )->nullable();
            $table->boolean( 'isAllRowRequired' )->nullable(); 

            $table->boolean( 'horizontalScroll' )->nullable();   
            $table->text( 'optionsCaption' )->nullable();
            $table->enum( 'cellType', ['dropdown', 'checkbox', 'radiogroup', 'text', 'comment', 'boolean', 'expression'] )->nullable();
            $table->integer( 'columnColCount' )->nullable();
            $table->integer( 'columnMinWidth' )->nullable();

            $table->integer( 'rowCount' )->nullable();
            $table->integer( 'minRowCount' )->nullable();
            $table->integer( 'maxRowCount' )->nullable();
            $table->string( 'keyName' )->nullable();
            $table->text( 'keyDuplicationError' )->nullable();
            $table->boolean( 'confirmDelete' )->nullable();   
            $table->text( 'confirmDeleteText' )->nullable();
            $table->text( 'addRowText' )->nullable();
            $table->text( 'removeRowText' )->nullable();

            $table->text( 'items' )->nullable();
            $table->integer( 'itemSize' )->nullable();
            $table->integer( 'colCount' )->nullable();

            $table->enum( 'state',['collapsed', 'expanded', 'firstExpanded'] )->nullable(); 
            $table->integer( 'innerIndent' )->nullable();

            $table->text( 'templateTitle' )->nullable();
            $table->text( 'templateDescription' )->nullable();
            $table->boolean( 'allowAddPanel' )->nullable();   
            $table->boolean( 'allowRemovePanel' )->nullable();  
            $table->integer( 'panelCount' )->nullable();
            $table->integer( 'minPanelCount' )->nullable();
            $table->integer( 'maxPanelCount' )->nullable();
            $table->enum( 'panelsState',['collapsed', 'expanded', 'firstExpanded'] )->nullable();
            $table->text( 'panelAddText' )->nullable();
            $table->text( 'panelRemoveText' )->nullable();
            $table->text( 'panelNextText' )->nullable();
            $table->text( 'panelPrevText' )->nullable();

            $table->string( 'showQuestionNumbers' )->nullable();
            $table->boolean( 'showRangeInProgress' )->nullable();   
            $table->enum( 'renderMode',['list', 'progressTop', 'progressBottom', 'progressTopBottom'] )->nullable();
            $table->enum( 'templateTitleLocation', ['top', 'bottom', 'left'] )->nullable();

            $table->string( 'dateFormat' )->nullable();

            $table->integer( 'step' )->nullable();
            $table->integer( 'rangeMin' )->nullable();
            $table->integer( 'rangeMax' )->nullable();

            $table->boolean( 'allowClear' )->nullable();  
            $table->integer( 'height' )->nullable();

            $table->string( 'emptyText' )->nullable();
      

            // $table->string( 'popupdescription' )->nullable();
            // $table->integer( 'rightIndent' )->nullable();
            // $table->boolean( 'isShowing' )->nullable();   
            // $table->string( 'comment' )->nullable();
            // $table->integer( 'renderWidth' )->nullable();
            // $table->enum( 'titleLocation', ['top', 'bottom', 'left'] )->nullable();
            // $table->text( 'value' )->nullable();
            // $table->enum( 'checkedValue', [true, false, 'indeterminate'] )->nullable();
            // $table->text( 'visibleRows' )->nullable();
            // $table->boolean( 'showHeader' )->nullable();   
            // $table->string( 'panels' )->nullable();
            // $table->string( 'template' )->nullable();


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
        Schema::dropIfExists('survey_elements');
    }
}
