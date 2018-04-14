<?php

namespace App\Http\Controllers;

use App\User;
use App\Survey;
use App\SurveyPage;
use App\SurveyElement;
use Illuminate\Http\Request;

class SurveyController extends Controller
{

    public function getSharedSurveys(Request $request, $id) {

		$totalSurveys = Survey::Where("share_all", '1')->count();

		$surveys = Survey::Where("share_all", '1')->get();

		$totalFiltered = $totalSurveys; 

		$page_number = empty($request->page_number) ? 0: $request->page_number;
		$page_size = empty($request->page_size) ? 5: $request->page_size;
		$order = empty($request->order) ? 'created_at' : $request->order;
		$dir = empty($request->dir) ? 'desc': $request->dir;
		$offset = $page_number * $page_size;

        if(empty($request->filter)) {            
			$surveys = Survey::Where("share_all", '1')
						->offset($offset)
                        ->limit($page_size)
                        ->orderBy($order,$dir)
                        ->get();
        } else {
            $filter = $request->filter; 

			$surveys = DB::table('surveys AS f')
							->leftJoin('users AS u', 'f.user_id', '=', 'u.id')  
							->select('f.id', 'f.name', 'f.description', 'f.user_id', 'f.created_at', 'f.updated_at')
							->where(function ($query) use($id) {
								$query->Where('f.share_all', '1');
								})
							->where(function($query) use($filter) {
								$query->where('f.id', 'like', "%{$filter}%")
								->orWhere('f.name', 'like', "%{$filter}%")
								->orWhere('f.description', 'LIKE',"%{$filter}%")
								->orWhere('u.first_name', 'LIKE',"%{$filter}%")
								->orWhere('u.last_name', 'LIKE',"%{$filter}%");
								})                                             
                            ->offset($offset)
                            ->limit($page_size)
                            ->orderBy($order,$dir)
                            ->get();

			$totalFiltered = DB::table('surveys AS f')
									->leftJoin('users AS u', 'f.user_id', '=', 'u.id')  
									->select('f.id', 'f.name', 'f.description', 'f.user_id', 'f.created_at', 'f.updated_at')
									->where(function ($query) use($id) {
										$query->Where('f.share_all', '1');
										})
									->where(function($query) use($filter) {
										$query->where('f.id', 'like', "%{$filter}%")
										->orWhere('f.name', 'like', "%{$filter}%")
										->orWhere('f.description', 'LIKE',"%{$filter}%")
										->orWhere('u.first_name', 'LIKE',"%{$filter}%")
										->orWhere('u.last_name', 'LIKE',"%{$filter}%");
										})    
                                    ->count();
        }		

		
		if(!empty($surveys))
        {
            foreach ($surveys as $survey)
            {
				$user = User::findOrFail($survey->user_id);
				$survey->user_name = $user->fullname();
            }
        }



        $response['data'] = $surveys;
        $response['page_number'] = $page_number;
        $response['page_size'] = $page_size;
        $response['total_counts'] = $totalFiltered;

		return response()->json( $response, 200 );
    }
    
    public function getAvailableSurveysByCompany(Request $request, $company) {
		$companySurveys =  DB::table('surveys AS f')
						   ->leftJoin('users AS u', 'f.user_id', '=', 'u.id')
						   ->select('f.*')
						   ->where(function ($query) use($company) {
							   $query->where('u.provider_company',$company);

						   })->get();

		return response()->json( $companySurveys, 200 );

    }
    
	public function getAvailableSurveysbyUser(Request $request, $id) {

		$totalSurveys = Survey::where( "user_id", $id )
					->orWhere("share_all", '1')->count();

		$surveys = Survey::where( "user_id", $id )
						->orWhere("share_all", '1')->get();

		$totalFiltered = $totalSurveys; 

		$page_number = empty($request->page_number) ? 0: $request->page_number;
		$page_size = empty($request->page_size) ? 5: $request->page_size;
		$order = empty($request->order) ? 'created_at' : $request->order;
		$dir = empty($request->dir) ? 'desc': $request->dir;
		$offset = $page_number * $page_size;

        if(empty($request->filter)) {            
			$surveys = Survey::where( "user_id", $id )
						->orWhere("share_all", '1')
						->offset($offset)
                        ->limit($page_size)
                        ->orderBy($order,$dir)
                        ->get();
        } else {
            $filter = $request->filter; 

			$surveys = DB::table('surveys AS f')
							->leftJoin('users AS u', 'f.user_id', '=', 'u.id')  
							->select('f.id', 'f.name', 'f.description', 'f.user_id', 'f.created_at', 'f.updated_at')
							->where(function ($query) use($id) {
								$query->where('f.user_id',$id)
									->orWhere('f.share_all', '1');
								})
							->where(function($query) use($filter) {
								$query->where('f.id', 'like', "%{$filter}%")
								->orWhere('f.name', 'like', "%{$filter}%")
								->orWhere('f.description', 'LIKE',"%{$filter}%")
								->orWhere('u.first_name', 'LIKE',"%{$filter}%")
								->orWhere('u.last_name', 'LIKE',"%{$filter}%");
								})                                             
                            ->offset($offset)
                            ->limit($page_size)
                            ->orderBy($order,$dir)
                            ->get();

			$totalFiltered = DB::table('surveys AS f')
									->leftJoin('users AS u', 'f.user_id', '=', 'u.id')  
									->select('f.id', 'f.name', 'f.description', 'f.user_id', 'f.created_at', 'f.updated_at')
									->where(function ($query) use($id) {
										$query->where('f.user_id',$id)
											->orWhere('f.share_all', '1');
										})
									->where(function($query) use($filter) {
										$query->where('f.id', 'like', "%{$filter}%")
										->orWhere('f.name', 'like', "%{$filter}%")
										->orWhere('f.description', 'LIKE',"%{$filter}%")
										->orWhere('u.first_name', 'LIKE',"%{$filter}%")
										->orWhere('u.last_name', 'LIKE',"%{$filter}%");
										})    
                                    ->count();
        }		

		
		if(!empty($surveys))
        {
            foreach ($surveys as $survey)
            {
				$user = User::findOrFail($id);
				$survey->user_name = $user->fullname();
            }
        }



        $response['data'] = $surveys;
        $response['page_number'] = $page_number;
        $response['page_size'] = $page_size;
        $response['total_counts'] = $totalFiltered;

		return response()->json( $response, 200 );
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//
		$surveys = Survey::all();
		foreach ($surveys as $survey) {
			$survey->user_name = $form->user->fullname();
        }
        
        if (isset($survey->questionTitleTemplate)) $survey->questionTitleTemplate = json_decode($survey->questionTitleTemplate);

		return response()->json( $survey );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
			'title' => 'required|min:1|max:60',
			'user_id' => 'required',
            'share_all' => 'required|boolean',
			'description' => 'sometimes|nullable|string|max:255'
        ], [
            'title.required' => "Please enter a name for the form",
        ]); 
        
        $survey = new Survey();

		$survey->title       = json_encode($request->title);
		$survey->description = $request->description;
		$survey->user_id	 = $request->user_id;
		$survey->share_all   = $request->share_all;
        $survey->save();
        
        $survey_page = new SurveyPage();
        $survey_page->survey_id = $survey->id;
        $survey_page->name = 'page1';
        
        $survey_page->save();

        $survey_pages = array();
        array_push($survey_pages, $survey_page);
        $survey->pages = $survey_pages;

        return response()->json( $survey );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $survey = Survey::findOrFail( $id );
		$survey->user_name = $survey->user->fullname();

        if (isset($survey->title)) $survey->title = json_decode($survey->title);
        if (isset($survey->startSurveyText)) $survey->startSurveyText = json_decode($survey->startSurveyText);
        if (isset($survey->pagePrevText)) $survey->pagePrevText = json_decode($survey->pagePrevText);
        if (isset($survey->pageNextText)) $survey->pageNextText = json_decode($survey->pageNextText);
        if (isset($survey->questionTitleTemplate)) $survey->questionTitleTemplate = json_decode($survey->questionTitleTemplate);
        if (isset($survey->triggers)) $survey->triggers = json_decode($survey->triggers);

		$pages = $survey->pages;
		// Fetch all pages
		foreach($pages as $page) {
            $elements = $this->getElementsOfPage($page->id);
            
            $new_elements = [];
			foreach($elements as $element) {
                array_push($new_elements, $this->getChildrenOfElement($element->id));
            }

            $page->elements = $new_elements;
        }



		return response()->json( $survey );
    }

    private function getChildrenOfElement($elementId) {
        $element = SurveyElement::findOrFail($elementId);

        if (isset($element)) {

            $elements = $this->getElementsOfElement($elementId);
            $templateElements = $this->getTemplateElementsOfElement($elementId);

            $new_elements = [];
            $new_templateElements = [];
            if (isset($elements)) {
                foreach($elements as $element) {
                    array_push($new_elements, $this->getChildrenOfElement($templateElement->id));
                }

                $element->elements = $new_elements;
            }

            if (isset($templateElements)) {
                foreach($templateElements as $templateElement) {
                    array_push($new_templateElements, $this->getChildrenOfElement($templateElement->id));
                }

                $element->templateElements = $new_templateElements;
            }


            if (isset($element->title)) $element->title = json_decode($element->title);
            if (isset($element->description)) $element->description = json_decode($element->description);
            if (isset($element->commentText)) $element->commentText = json_decode($element->commentText);
            if (isset($element->defaultValue)) $element->defaultValue = json_decode($element->defaultValue);
            if (isset($element->correctAnswer)) $element->correctAnswer = json_decode($element->correctAnswer); 
            if (isset($element->requiredErrorText)) $element->requiredErrorText = json_decode($element->requiredErrorText);
            if (isset($element->validators)) $element->validators = json_decode($element->validators);
            if (isset($element->choices)) $element->choices = json_decode($element->choices);
            if (isset($element->choicesByUrl)) $element->choicesByUrl = json_decode($element->choicesByUrl);
            if (isset($element->otherText)) $element->otherText = json_decode($element->otherText);
            if (isset($element->otherErrorText)) $element->otherErrorText = json_decode($element->otherErrorText);
            if (isset($element->placeHolder)) $element->placeHolder = json_decode($element->placeHolder);
            if (isset($element->cols)) $element->cols = json_decode($element->cols);
            if (isset($element->rows)) $element->rows = json_decode($element->rows);
            if (isset($element->rateValues)) $element->rateValues = json_decode($element->rateValues);
            if (isset($element->minRateDescription)) $element->minRateDescription = json_decode($element->minRateDescription);
            if (isset($element->maxRateDescription)) $element->maxRateDescription = json_decode($element->maxRateDescription);
            if (isset($element->label)) $element->label = json_decode($element->label);
            if (isset($element->html)) $element->html = json_decode($element->html);
            if (isset($element->format)) $element->format = json_decode($element->format);
            if (isset($element->columns)) $element->columns = json_decode($element->columns);
            if (isset($element->cells)) $element->cells = json_decode($element->cells);
            if (isset($element->optionsCaption)) $element->optionsCaption = json_decode($element->optionsCaption);
            if (isset($element->keyDuplicationError)) $element->keyDuplicationError = json_decode($element->keyDuplicationError);
            if (isset($element->confirmDeleteText)) $element->confirmDeleteText = json_decode($element->confirmDeleteText);
            if (isset($element->addRowText)) $element->addRowText = json_decode($element->addRowText);
            if (isset($element->removeRowText)) $element->removeRowText = json_decode($element->removeRowText);
            if (isset($element->items)) $element->items = json_decode($element->items);
            if (isset($element->templateTitle)) $element->templateTitle = json_decode($element->templateTitle);
            if (isset($element->templateDescription)) $element->templateDescription = json_decode($element->templateDescription);
            if (isset($element->panelAddText)) $element->panelAddText = json_decode($element->panelAddText);
            if (isset($element->panelRemoveText)) $element->panelRemoveText = json_decode($element->panelRemoveText);
            if (isset($element->panelPrevText)) $element->panelPrevText = json_decode($element->panelPrevText);
            if (isset($element->panelNextText)) $element->panelNextText = json_decode($element->panelNextText);
            
        }

        return $element;
    }

    private function getElementsOfPage($pageId) {
        $elements = SurveyElement::where( "survey_page_id", $pageId )
                                ->whereNull( "parent_id")
                                ->get();
        return $elements;
    }

    private function getElementsOfElement($elementId) {
        $elements = SurveyElement::where( "parent_id", $elementId )
                                ->where( "type",'!=', "paneldynamic")
                                ->get();
        return $elements;
    }

    private function getTemplateElementsOfElement($elementId) {
        $templateElements = SurveyElement::where( "parent_id", $elementId )
                                ->where( "type", "paneldynamic")
                                ->get();
        return $templateElements;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
			'title' => 'required|min:1|max:60',
			'user_id' => 'required',
            'share_all' => 'required|boolean',
			'description' => 'sometimes|nullable|string|max:255'
        ], [
            'title.required' => "Please enter a name for the form",
        ]);
         
		$survey = survey::findOrFail( $id );

        if ( $request->user_id != $survey->user_id ){
            throw new \App\Exceptions\InvalidAccessException();
        }

        $survey_pages = $survey->pages;

		foreach($survey_pages as $survey_page) {
            SurveyElement::where('survey_page_id', $survey_page->id)->delete();
            //$survey_page->elements()->delete();
        }
        
		$survey->pages()->delete();
        
        
        foreach( $request->pages as $page) {

            $new_survey_page = new SurveyPage();

            $new_survey_page->survey_id = $id;
            $new_survey_page->name = $page['name'];

            if (isset($page['id'])) $new_survey_page->id = $page['id'];

            if (isset($page['visible'])) $new_survey_page->visible = $page['visible'];
            if (isset($page['visibleIf'])) $new_survey_page->visibleIf = $page['visibleIf'];
            if (isset($page['questionTitleLocation'])) $new_survey_page->questionTitleLocation = $page['questionTitleLocation']; 
            if (isset($page['title'])) $new_survey_page->title = $page['title']; 
            if (isset($page['description'])) $new_survey_page->description = $page['description'];
            if (isset($page['navigationButtonsVisibility'])) $new_survey_page->navigationButtonsVisibility = $page['navigationButtonsVisibility'];
            if (isset($page['questionsOrder'])) $new_survey_page->questionsOrder = $page['questionsOrder']; 
            if (isset($page['maxTimeToFinish'])) $new_survey_page->maxTimeToFinish = $page['maxTimeToFinish']; 
            
            $new_survey_page->save();

            if (isset($page['elements'])) {
                foreach ( $page['elements'] as $element ){
                    $this->newElement($element, $new_survey_page->id);
                }
            }

        }

        if (isset($request->id)) $survey->id = $request->id;
        if (isset($request->description)) $survey->description = $request->description;
        if (isset($request->locale)) $survey->locale = $request->locale;
        if (isset($request->focusFirstQuestionAutomatic)) $survey->focusFirstQuestionAutomatic = $request->focusFirstQuestionAutomatic;
        if (isset($request->completedHtml)) $survey->completedHtml = $request->completedHtml;
        if (isset($request->completedBeforeHtml)) $survey->completedBeforeHtml = $request->completedBeforeHtml;
        if (isset($request->loadingHtml)) $survey->loadingHtml = $request->loadingHtml;
        if (isset($request->cookieName)) $survey->cookieName = $request->cookieName;
        if (isset($request->sendResultOnPageNext)) $survey->sendResultOnPageNext = $request->sendResultOnPageNext;
        if (isset($request->showNavigationButtons)) $survey->showNavigationButtons = $request->showNavigationButtons;
        if (isset($request->showPrevButton)) $survey->showPrevButton = $request->showPrevButton;
        if (isset($request->showTitle)) $survey->showTitle = $request->showTitle;
        if (isset($request->showPageTitles)) $survey->showPageTitles = $request->showPageTitles;
        if (isset($request->showCompletedPage)) $survey->showCompletedPage = $request->showCompletedPage;
        if (isset($request->questionsOrder)) $survey->questionsOrder = $request->questionsOrder;
        if (isset($request->showPageNumbers)) $survey->showPageNumbers = $request->showPageNumbers;
        if (isset($request->showQuestionNumbers)) $survey->showQuestionNumbers = $request->showQuestionNumbers;
        if (isset($request->questionTitleLocation)) $survey->questionTitleLocation = $request->questionTitleLocation;
        if (isset($request->questionErrorLocation)) $survey->questionErrorLocation = $request->questionErrorLocation;
        if (isset($request->showProgressBar)) $survey->showProgressBar = $request->showProgressBar;
        if (isset($request->mode)) $survey->mode = $request->mode;
        if (isset($request->storeOthersAsComment)) $survey->storeOthersAsComment = $request->storeOthersAsComment;
        if (isset($request->maxTextLength)) $survey->maxTextLength = $request->maxTextLength;
        if (isset($request->maxOthersLength)) $survey->maxOthersLength = $request->maxOthersLength;
        if (isset($request->goNextPageAutomatic)) $survey->goNextPageAutomatic = $request->goNextPageAutomatic;
        if (isset($request->clearInvisibleValues)) $survey->clearInvisibleValues = $request->clearInvisibleValues;
        if (isset($request->checkErrorsMode)) $survey->checkErrorsMode = $request->checkErrorsMode;
        if (isset($request->startSurveyText)) $survey->startSurveyText = json_encode($request->startSurveyText);
        if (isset($request->pagePrevText)) $survey->pagePrevText = json_encode($request->pagePrevText);
        if (isset($request->pageNextText)) $survey->pageNextText = json_encode($request->pageNextText);
        if (isset($request->completeText)) $survey->completeText = $request->completeText;
        if (isset($request->requiredText)) $survey->requiredText = $request->requiredText;
        if (isset($request->questionStartIndex)) $survey->questionStartIndex = $request->questionStartIndex;
        if (isset($request->questionTitleTemplate)) $survey->questionTitleTemplate = json_encode($request->questionTitleTemplate);
        if (isset($request->firstPageIsStarted)) $survey->firstPageIsStarted = $request->firstPageIsStarted;
        if (isset($request->isSinglePage)) $survey->isSinglePage = $request->isSinglePage;
        if (isset($request->maxTimeToFinish)) $survey->maxTimeToFinish = $request->maxTimeToFinish;
        if (isset($request->maxTimeToFinishPage)) $survey->maxTimeToFinishPage = $request->maxTimeToFinishPage;
        if (isset($request->showTimerPanel)) $survey->showTimerPanel = $request->showTimerPanel;
        if (isset($request->showTimerPanelMode)) $survey->showTimerPanelMode = $request->showTimerPanelMode;
        if (isset($request->triggers)) $survey->triggers = json_encode($request->triggers);

        $survey->title = json_encode($request->title);
        $survey->user_id = $request->user_id;
        $survey->share_all = $request->share_all;
  
        $survey->save();

        return $this->show($id);
    }

    private function newElement($element, $page_id, $parent_id=null) {

        $new_survey_element = new SurveyElement();

        if (isset($element['id'])) $new_survey_element->id = $element['id'];

        $new_survey_element->survey_page_id = $page_id;
        $new_survey_element->name = $element['name'];
        $new_survey_element->type = $element['type'];

        if (isset($parent_id)) $new_survey_element->parent_id = $parent_id;

        if (isset($element['visible'])) $new_survey_element->visible = $element['visible'];
        if (isset($element['visibleIf'])) $new_survey_element->visibleIf = $element['visibleIf'];
        if (isset($element['width'])) $new_survey_element->width = $element['width'];
        if (isset($element['startWithNewLine'])) $new_survey_element->startWithNewLine = $element['startWithNewLine'];
        if (isset($element['indent'])) $new_survey_element->indent = $element['indent'];
        if (isset($element['title'])) $new_survey_element->title = json_encode($element['title']);
        if (isset($element['description'])) $new_survey_element->description = json_encode($element['description']);
        if (isset($element['commentText'])) $new_survey_element->commentText = json_encode($element['commentText']);
        if (isset($element['valueName'])) $new_survey_element->valueName = $element['valueName'];
        if (isset($element['enableIf'])) $new_survey_element->enableIf = $element['enableIf'];
        if (isset($element['defaultValue'])) $new_survey_element->defaultValue = json_encode($element['defaultValue']);
        if (isset($element['correctAnswer'])) $new_survey_element->correctAnswer = json_encode($element['correctAnswer']);
        if (isset($element['isRequired'])) $new_survey_element->isRequired = $element['isRequired'];
        if (isset($element['requiredErrorText'])) $new_survey_element->requiredErrorText = json_encode($element['requiredErrorText']);
        if (isset($element['readOnly'])) $new_survey_element->readOnly = $element['readOnly'];
        if (isset($element['validators'])) $new_survey_element->validators = json_encode($element['validators']);

        if (isset($element['hasOther'])) $new_survey_element->hasOther = $element['hasOther'];
        if (isset($element['choices'])) $new_survey_element->choices = json_encode($element['choices']);
        if (isset($element['choicesOrder'])) $new_survey_element->choicesOrder = $element['choicesOrder'];
        if (isset($element['choicesByUrl'])) $new_survey_element->choicesByUrl = json_encode($element['choicesByUrl']);
        if (isset($element['otherText'])) $new_survey_element->otherText = json_encode($element['otherText']);
        if (isset($element['otherErrorText'])) $new_survey_element->otherErrorText = json_encode($element['otherErrorText']);
        if (isset($element['storeOthersAsComment'])) $new_survey_element->storeOthersAsComment = $element['storeOthersAsComment'];
        if (isset($element['renderAs'])) $new_survey_element->renderAs = $element['renderAs'];

        if (isset($element['titleLocation'])) $new_survey_element->titleLocation = $element['titleLocation'];
        if (isset($element['questionTitleLocation'])) $new_survey_element->questionTitleLocation = $element['questionTitleLocation'];
        if (isset($element['inputType'])) $new_survey_element->inputType = $element['inputType'];
        if (isset($element['size'])) $new_survey_element->size = $element['size'];
        if (isset($element['maxLength'])) $new_survey_element->maxLength = $element['maxLength'];
        if (isset($element['placeHolder'])) $new_survey_element->placeHolder = json_encode($element['placeHolder']);
        if (isset($element['inputFormat'])) $new_survey_element->inputFormat = $element['inputFormat'];
        if (isset($element['inputMask'])) $new_survey_element->inputMask = $element['inputMask'];

        if (isset($element['hasComment'])) $new_survey_element->hasComment = $element['hasComment'];
        if (isset($element['showLabel'])) $new_survey_element->showLabel = $element['showLabel'];
 
        if (isset($element['showValues'])) $new_survey_element->showValues = $element['showValues'];
        if (isset($element['ratingTheme'])) $new_survey_element->ratingTheme = $element['ratingTheme'];

        if (isset($element['cols'])) $new_survey_element->cols = json_encode($element['cols']);
        if (isset($element['rows'])) $new_survey_element->rows = json_encode($element['rows']);

        if (isset($element['rateValues'])) $new_survey_element->rateValues = $element['rateValues'];
        if (isset($element['rateMin'])) $new_survey_element->rateMin = $element['rateMin'];
        if (isset($element['rateMax'])) $new_survey_element->rateMax = $element['rateMax'];
        if (isset($element['rateStep'])) $new_survey_element->rateStep = $element['rateStep'];
        if (isset($element['minRateDescription'])) $new_survey_element->minRateDescription = json_encode($element['minRateDescription']);
        if (isset($element['maxRateDescription'])) $new_survey_element->maxRateDescription = json_encode($element['maxRateDescription']);

        if (isset($element['showTitle'])) $new_survey_element->showTitle = $element->$element['showTitle'];
        if (isset($element['label'])) $new_survey_element->label = json_encode($element['label']);
        if (isset($element['valueTrue'])) $new_survey_element->valueTrue = $element['valueTrue'];
        if (isset($element['valueFalse'])) $new_survey_element->valueFalse = $element['valueFalse'];
        
        if (isset($element['html'])) $new_survey_element->html = $element['html'];

        if (isset($element['expression'])) $new_survey_element->expression = $element['expression'];
        if (isset($element['format'])) $new_survey_element->format = json_encode($element['format']);
        if (isset($element['displayStyle'])) $new_survey_element->displayStyle = $element['displayStyle'];
        if (isset($element['currency'])) $new_survey_element->currency = $element['currency'];
        if (isset($element['useGrouping'])) $new_survey_element->useGrouping = $element['useGrouping'];
        
        if (isset($element['showPreview'])) $new_survey_element->showPreview = $element['showPreview'];
        if (isset($element['allowMultiple'])) $new_survey_element->allowMultiple = $element['allowMultiple'];
        if (isset($element['imageHeight'])) $new_survey_element->imageHeight = $element['imageHeight'];
        if (isset($element['imageWidth'])) $new_survey_element->imageWidth = $element['imageWidth'];
        if (isset($element['storeDataAsText'])) $new_survey_element->storeDataAsText = $element['storeDataAsText'];
        if (isset($element['maxSize'])) $new_survey_element->maxSize = $element['maxSize'];

        if (isset($element['columns'])) $new_survey_element->columns = json_encode($element['columns']);
        if (isset($element['cells'])) $new_survey_element->cells = json_encode($element['cells']);
        if (isset($element['isAllRowRequired'])) $new_survey_element->isAllRowRequired = $element['isAllRowRequired'];

        if (isset($element['horizontalScroll'])) $new_survey_element->horizontalScroll = $element['horizontalScroll'];
        if (isset($element['optionsCaption'])) $new_survey_element->optionsCaption = json_encode($element['optionsCaption']);
        if (isset($element['cellType'])) $new_survey_element->cellType = $element['cellType'];
        if (isset($element['columnColCount'])) $new_survey_element->columnColCount = $element['columnColCount'];
        if (isset($element['columnMinWidth'])) $new_survey_element->columnMinWidth = $element['columnMinWidth'];

        if (isset($element['rowCount'])) $new_survey_element->rowCount = $element['rowCount'];
        if (isset($element['minRowCount'])) $new_survey_element->minRowCount = $element['minRowCount'];
        if (isset($element['maxRowCount'])) $new_survey_element->maxRowCount = $element['maxRowCount'];
        if (isset($element['keyName'])) $new_survey_element->keyName = $element['keyName'];
        if (isset($element['keyDuplicationError'])) $new_survey_element->keyDuplicationError = json_encode($element['keyDuplicationError']);
        if (isset($element['confirmDelete'])) $new_survey_element->confirmDelete = $element['confirmDelete'];
        if (isset($element['confirmDeleteText'])) $new_survey_element->confirmDeleteText = json_encode($element['confirmDeleteText']);
        if (isset($element['addRowText'])) $new_survey_element->addRowText = json_encode($element['addRowText']);
        if (isset($element['removeRowText'])) $new_survey_element->removeRowText = json_encode($element['removeRowText']);

        if (isset($element['items'])) $new_survey_element->items = json_encode($element['items']);
        if (isset($element['itemSize'])) $new_survey_element->itemSize = $element['itemSize'];
        if (isset($element['colCount'])) $new_survey_element->colCount = $element['colCount'];

        if (isset($element['state'])) $new_survey_element->state = $element['state'];
        if (isset($element['innerIndent'])) $new_survey_element->innerIndent = $element['innerIndent'];

        if (isset($element['templateTitle'])) $new_survey_element->templateTitle = json_encode($element['templateTitle']);
        if (isset($element['templateDescription'])) $new_survey_element->templateDescription = json_encode($element['templateDescription']);
        if (isset($element['allowAddPanel'])) $new_survey_element->allowAddPanel = $element['allowAddPanel'];
        if (isset($element['allowRemovePanel'])) $new_survey_element->allowRemovePanel = $element['allowRemovePanel'];
        if (isset($element['panelCount'])) $new_survey_element->panelCount = $element['panelCount'];
        if (isset($element['minPanelCount'])) $new_survey_element->minPanelCount = $element['minPanelCount'];
        if (isset($element['maxPanelCount'])) $new_survey_element->maxPanelCount = $element['maxPanelCount'];
        if (isset($element['panelsState'])) $new_survey_element->panelsState = $element['panelsState'];
        if (isset($element['panelAddText'])) $new_survey_element->panelAddText = json_encode($element['panelAddText']);
        if (isset($element['panelRemoveText'])) $new_survey_element->panelRemoveText = json_encode($element['panelRemoveText']);
        if (isset($element['panelPrevText'])) $new_survey_element->panelPrevText = json_encode($element['panelPrevText']);
        if (isset($element['panelNextText'])) $new_survey_element->panelNextText = json_encode($element['panelNextText']);

        if (isset($element['showQuestionNumbers'])) $new_survey_element->showQuestionNumbers = $element['showQuestionNumbers'];
        if (isset($element['showRangeInProgress'])) $new_survey_element->showRangeInProgress = $element['showRangeInProgress'];
        if (isset($element['renderMode'])) $new_survey_element->renderMode = $element['renderMode'];
        if (isset($element['templateTitleLocation'])) $new_survey_element->templateTitleLocation = $element['templateTitleLocation'];

        if (isset($element['dateFormat'])) $new_survey_element->dateFormat = $element['dateFormat'];

        if (isset($element['step'])) $new_survey_element->step = $element['step'];
        if (isset($element['rangeMin'])) $new_survey_element->rangeMin = $element['rangeMin'];
        if (isset($element['rangeMax'])) $new_survey_element->rangeMax = $element['rangeMax'];

        if (isset($element['allowClear'])) $new_survey_element->allowClear = $element['allowClear'];
        if (isset($element['height'])) $new_survey_element->height = $element['height'];

        if (isset($element['emptyText'])) $new_survey_element->emptyText = $element['emptyText'];
        
        $new_survey_element->save();

        if (isset($element['elements'])) {
            foreach ($element['elements'] as $child_element) {
                $this->newElement($child_element, $page_id, $new_survey_element->id);
            }
        }

        if (isset($element['templateElements'])) {
            foreach ($element['templateElements'] as $child_templat_element) {
                $this->newElement($child_templat_element, $page_id, $new_survey_element->id);
            }
        }

    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $survey = Survey::findOrFail( $id );

		$pages = $survey->pages;

		foreach($pages as $page) {
			$pages->elements->delete();
		}


		$survey->pages->delete();

		$survey->delete();

		return response()->json( null, 204 );
    }
}
