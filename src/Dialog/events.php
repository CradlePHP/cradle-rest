<?php //-->
/**
 * This file is part of a Custom Project.
 * (c) 2016-2018 Acme Products Inc.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

/**
 * Creates a Session
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('session-create', function ($request, $response) {
    //set session as schema
    $request->setStage('schema', 'session');

    //trigger model create
    $this->trigger('system-model-create', $request, $response);
});

/**
 * Make a step to generate dialog pages
 *
 * @param Request $request
 * @param Request $response
 */
$cradle->on('rest-render-dialog-page', function ($request, $response) {
    $content = cradle('cradlephp/cradle-rest')->template('Dialog', '_page', [
        'page' => $response->getPage(),
        'results' => $response->getResults(),
        'content' => $response->getContent()
    ]);

    $response->setContent($content);
});