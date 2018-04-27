<?php //-->
/**
 * This file is part of a Custom Project.
 * (c) 2016-2018 Acme Products Inc.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Package\System\Schema;
use Cradle\Package\System\Exception;

use Cradle\Http\Request;
use Cradle\Http\Response;

/**
 * Creates an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-create', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model create
    $this->trigger('system-model-create', $request, $response);
});

/**
 * Gets the App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-detail', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model detail
    $this->trigger('system-model-detail', $request, $response);
});

/**
 * Removes an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-remove', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model remove
    $this->trigger('system-model-remove', $request, $response);
});

/**
 * Restores an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-restore', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model restore
    $this->trigger('system-model-restore', $request, $response);
});

/**
 * Searches an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-search', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model search
    $this->trigger('system-model-search', $request, $response);
});

/**
 * Updates an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-update', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model update
    $this->trigger('system-model-update', $request, $response);
});