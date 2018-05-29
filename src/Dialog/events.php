<?php //-->
/**
 * This file is part of a Cradle Rest Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

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
