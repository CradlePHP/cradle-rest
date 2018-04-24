<?php //-->
/**
 * This file is part of a Custom Package.
 */

// Back End Controllers
use Cradle\Package\System\Schema;

/**
 * Render App Create Page
 * 
 * @param Request $request
 * @param Response $response
 */
$cradle->get('/admin/app/create', function ($request, $response) {

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
    //----------------------------//
    // 1. Prepare data
    if (!$request->hasStage('filter')) {
        $request->setStage('filter', 'app_active', 1);
    }

    //trigger job
    $this->trigger('app-search', $request, $response);

    //if we only want the raw data
    if ($request->getStage('render') === 'false') {
        return;
    }

    $data = array_merge($request->getStage(), $response->getResults());

    //----------------------------//
    // 2. Render Template
    //Render body
    $class = 'page-app-search';
    $title = $this->package('global')->translate('Applications');

    $body = $this
        ->package('cradlephp/cradle-rest')
        ->template('App', 'search', $data);

    //Set Content
    $response
        ->setPage('title', $title)
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //Render blank page
    $this->trigger('admin-render-page', $request, $response);
});
