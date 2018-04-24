<?php //-->
/**
 * This file is part of a Custom Package.
 */

// Back End Controllers
use Cradle\Package\System\Schema;

/**
 * Render App Search
 * 
 * @param Request $request
 * @param Response $response
 */
$cradle->get('/admin/app/search', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data
    $schema = Schema::i('app');

    //if this is a return back from processing
    //this form and it's because of an error
    if ($response->isError()) {
        //pass the error messages to the template
        $response->setFlash($response->getMessage(), 'error');
    }

    //set a default range
    if (!$request->hasStage('range')) {
        $request->setStage('range', 50);
    }

    //filter possible filter options
    //we do this to prevent SQL injections
    if (is_array($request->getStage('filter'))) {
        foreach ($request->getStage('filter') as $key => $value) {
            //if invalid key format or there is no value
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key) || !strlen($value)) {
                $request->removeStage('filter', $key);
            }
        }
    }

    //filter possible sort options
    //we do this to prevent SQL injections
    if (is_array($request->getStage('order'))) {
        foreach ($request->getStage('order') as $key => $value) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                $request->removeStage('order', $key);
            }
        }
    }

    //trigger job
    $this->trigger('app-search', $request, $response);

    //if we only want the raw data
    if ($request->getStage('render') === 'false') {
        return;
    }

    //form the data
    $data = array_merge(
        //we need to case for things like
        //filter and sort on the template
        $request->getStage(),
        //this is from the search event
        $response->getResults()
    );

    //also pass the schema to the template
    $data['schema'] = $schema->getAll();

    //if there's an active field
    if ($data['schema']['active']) {
        //find it
        foreach ($data['schema']['filterable'] as $i => $filter) {
            //if we found it
            if ($filter === $data['schema']['active']) {
                //remove it from the filters
                unset($data['schema']['filterable'][$i]);
            }
        }

        //reindex filterable
        $data['schema']['filterable'] = array_values($data['schema']['filterable']);
    }

    $data['filterable_relations'] = [];
    foreach ($data['schema']['relations'] as $relation) {
        if ($relation['many'] < 2) {
            $data['filterable_relations'][] = $relation;
        }
    }

    //determine valid relations
    $data['valid_relations'] = [];
    $this->trigger('system-schema-search', $request, $response);
    foreach ($response->getResults('rows') as $relation) {
        $data['valid_relations'][] = $relation['name'];
    }

    //----------------------------//
    // 2. Render Template
    //set the class name
    $class = 'page-admin-app-search page-admin';

    //render the body
    $body = $this
        ->package('cradlephp/cradle-rest')
        ->template('App', 'search', $data, [
            'search_head',
            'search_form',
            'search_filters',
            'search_actions',
            'search_row_format',
            'search_row_actions'
        ]);

    //set content
    $response
        ->setPage('title', $data['schema']['plural'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }
    
    //render page
    $this->trigger('admin-render-page', $request, $response);
});

/**
 * Render App Update Page
 * 
 * @param Request $request
 * @param Response $response
 */
$cradle->get('/admin/app/update/:app_id', function ($request, $response) {

});

/**
 * Render App Remove Page
 * 
 * @param Request $request
 * @param Response $response
 */
$cradle->get('/admin/app/remove/:app_id', function ($request, $response) {

});

/**
 * Render App Restore Page
 * 
 * @param Request $request
 * @param Response $response
 */
$cradle->get('/admin/app/restore/:app_id', function ($request, $response) {

});

/**
 * Render App Search Page
 * 
 * @param Request $request
 * @param Response $response
 */
$cradle->get('/admin/app/search', function ($request, $response) {

});