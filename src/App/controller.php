<?php //-->
/**
 * This file is part of a Custom Project.
 * (c) 2016-2018 Acme Products Inc.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

// Back End Controllers
use Cradle\Http\Request;
use Cradle\Http\Response;
use Cradle\Package\System\Schema;

/**
 * Render App Create Page
 * 
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/app/create', function ($request, $response) {
    //----------------------------//
    // 1. Prepare data
    $data = ['item' => $request->getPost()];

    if ($response->isError()) {
        $response->setFlash($response->getMessage(), 'error');
        $data['errors'] = $response->getValidation();
    }

    // role request
    $roleRequest = Request::i()->load();
    // role response
    $roleResponse = Response::i()->load();

    // get auth id
    $auth = $request->getSession('me', 'auth_id');

    // set schema
    $roleRequest->setStage('schema', 'role');
    // set filter
    $roleRequest->setStage('filter', 'auth_id', $auth);
    // get the auth role
    $this->trigger('system-model-search', $roleRequest, $roleResponse);

    // get the auth roles
    $data['roles'] = $roleResponse->getResults('rows');

    //----------------------------//
    // 2. Render Template
    //Render body
    $class = 'page-app-create';
    $data['title'] = $this->package('global')->translate('Application Create');
    $data['action'] = 'create';

    $body = $this
        ->package('cradlephp/cradle-rest')
        ->template('App', 'form', $data);

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //Render blank page
    $this->trigger('admin-render-page', $request, $response);
});

/**
 * Render App Detail Page
 * 
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/app/detail/:app_id', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data

    //----------------------------//
    // 2. Process Request
    $this->trigger('app-detail', $request, $response);

    // get the results
    $data['item'] = $response->getResults();

    //----------------------------//
    // 3. Interpret Results
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        return $this->package('global')->redirect('/admin/app/search');
    }

    //----------------------------//
    // 4. Render Template
    //Render body
    $class = 'page-app-detail';
    $data['title'] = $this->package('global')->translate('Application Detail');

    $body = $this
        ->package('cradlephp/cradle-rest')
        ->template('App', 'detail', $data);

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //Render blank page
    $this->trigger('admin-render-page', $request, $response);
});

/**
 * Render App Update Page
 * 
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/app/update/:app_id', function ($request, $response) {
    //----------------------------//
    // 1. Prepare data
    $this->trigger('app-detail', $request, $response);

    // get role details
    $data['item'] = $response->getResults();

    // if app does not exists
    if (empty($data['item'])) {
        $this->package('global')->flash('Not Found', 'error');

        return $this
            ->package('global')
            ->redirect('/admin/app/search');
    }

    if (!empty($request->getPost())) {
        // get post stored as item
        $data['item'] = $request->getPost();
        // get any errors
        $data['errors'] = $response->getValidation();
    }

    // role request
    $roleRequest = Request::i()->load();
    // role response
    $roleResponse = Response::i()->load();

    // get auth id
    $auth = $request->getSession('me', 'auth_id');

    // set schema
    $roleRequest->setStage('schema', 'role');
    // set filter
    $roleRequest->setStage('filter', 'auth_id', $auth);
    // get the auth role
    $this->trigger('system-model-search', $roleRequest, $roleResponse);

    // get the auth roles
    $data['roles'] = $roleResponse->getResults('rows');

    //----------------------------//
    // 2. Render Template
    //Render body
    $class = 'page-app-update';
    $data['title'] = $this->package('global')->translate('Application Update');

    $body = $this
        ->package('cradlephp/cradle-rest')
        ->template('App', 'form', $data);

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //Render blank page
    $this->trigger('admin-render-page', $request, $response);
});

/**
 * Render App Remove Page
 * 
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/app/remove/:app_id', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data

    //----------------------------//
    // 2. Process Request
    $this->trigger('app-remove', $request, $response);

    //----------------------------//
    // 3. Interpret Results
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        return $this->package('global')->redirect('/admin/app/search');
    }

    //redirect
    $redirect = '/admin/app/search';

    //if there is a specified redirect
    if ($request->getStage('redirect')) {
        //set the redirect
        $redirect = $request->getStage('redirect');
    }

    if ($response->isError()) {
        //add a flash
        $this->package('global')->flash($response->getMessage(), 'error');
    } else {
        //add a flash
        $message = $this->package('global')->translate('Application was Removed');
        $this->package('global')->flash($message, 'success');
    }

    $this->package('global')->redirect($redirect);
});

/**
 * Render App Restore Page
 * 
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/app/restore/:app_id', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data

    //----------------------------//
    // 2. Process Request
    $this->trigger('app-restore', $request, $response);

    //----------------------------//
    // 3. Interpret Results
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        return $this->package('global')->redirect('/admin/app/search');
    }

    //redirect
    $redirect = '/admin/app/search';

    //if there is a specified redirect
    if ($request->getStage('redirect')) {
        //set the redirect
        $redirect = $request->getStage('redirect');
    }

    if ($response->isError()) {
        //add a flash
        $this->package('global')->flash($response->getMessage(), 'error');
    } else {
        //add a flash
        $message = $this->package('global')->translate('Application was Restored');
        $this->package('global')->flash($message, 'success');
    }

    $this->package('global')->redirect($redirect);
});

/**
 * Render App Refresh Page
 * 
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/app/refresh/:app_id', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data
    // update app token
    $request->setStage('app_token', md5(uniqid() . uniqid()));
    // update app secret
    $request->setStage('app_secret', md5(uniqid() . uniqid()));

    //----------------------------//
    // 2. Process Request
    $this->trigger('app-update', $request, $response);

    //----------------------------//
    // 3. Interpret Results
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        return $this->package('global')->redirect('/admin/app/search');
    }

    //redirect
    $redirect = '/admin/app/search';

    //if there is a specified redirect
    if ($request->getStage('redirect')) {
        //set the redirect
        $redirect = $request->getStage('redirect');
    }

    if ($response->isError()) {
        //add a flash
        $this->package('global')->flash($response->getMessage(), 'error');
    } else {
        //add a flash
        $message = $this->package('global')->translate('Application was Refreshed');
        $this->package('global')->flash($message, 'success');
    }

    $this->package('global')->redirect($redirect);
});

/**
 * Render App Search Page
 * 
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/app/search', function ($request, $response) {
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

/**
 * Process App Create Request
 * 
 * @param Request $request
 * @param Response $response
 */
$this->post('/admin/app/create', function ($request, $response) {
    //----------------------------//
    // 1. Prepare data
    // get profile id from session
    $request->setStage('auth_id', $request->getSession('me', 'auth_id'));

    // if domain is not set
    if (!$request->hasStage('app_domain') || !$request->getStage('app_domain')) {
        // set default domain
        $request->setStage('app_domain', '*');
    }

    // set app token
    $request->setStage('app_token', md5(uniqid() . uniqid()));
    // set app secret
    $request->setStage('app_secret', md5(uniqid() . uniqid()));

    // format permissions to json
    if ($request->hasStage('app_permissions')) {
        try {
            $request->setStage(
                'app_permissions', 
                json_encode($request->getStage('app_permissions'))
            );
        } catch(\Exception $e) {}
    }

    //----------------------------//
    // 2. Process Request
    $this->trigger('app-create', $request, $response);

    //----------------------------//
    // 3. Interpret Results
    if ($response->isError()) {
        //add a flash
        $this->package('global')->flash('Invalid Data', 'error');
        return $this->routeTo('get', '/admin/app/create', $request, $response);
    }

    //it was good
    //add a flash
    $this->package('global')->flash('Application was Created', 'success');

    if ($request->hasStage('redirect')) {
        return $this
            ->package('global')
            ->redirect($request->getStage('redirect'));
    }

    //redirect
    $this->package('global')->redirect('/admin/app/search');
});

/**
 * Process App Update Request
 * 
 * @param Request $request
 * @param Response $response
 */
$this->post('/admin/app/update/:app_id', function ($request, $response) {
    //----------------------------//
    // 1. Process Request
    // if domain is not set
    if (!$request->hasStage('app_domain') || !$request->getStage('app_domain')) {
        // set default domain
        $request->setStage('app_domain', '*');
    }

    // format permissions to json
    if ($request->hasStage('app_permissions')) {
        try {
            $request->setStage(
                'app_permissions', 
                json_encode($request->getStage('app_permissions'))
            );
        } catch(\Exception $e) {}
    } else {
        $request->setStage('app_permissions', '[]');
    }

    //----------------------------//
    // 2. Process Request
    $this->trigger('app-update', $request, $response);    

    //----------------------------//
    // 3. Interpret Results
    if ($response->isError()) {
        $route = '/admin/app/update/' . $request->getStage('app_id');
        return $this->routeTo('get', $route, $request, $response);
    }

    //it was good
    //add a flash
    $this->package('global')->flash('Application was Updated', 'success');

    if ($request->hasStage('redirect')) {
        return $this
            ->package('global')
            ->redirect($request->getStage('redirect'));
    }

    //redirect
    $this->package('global')->redirect('/admin/app/search');
});