<?php //-->
/**
 * This file is part of a Cradle Rest Package.
 */

use Cradle\Storm\SqlFactory;

use Cradle\Package\System\Schema;
use Cradle\Package\System\Exception;

use Cradle\Http\Request;
use Cradle\Http\Response;

/**
 * $ cradle package install cradlephp/cradle-rest
 * $ cradle package install cradlephp/cradle-rest 1.0.0
 * $ cradle cradlephp/cradle-rest install
 * $ cradle cradlephp/cradle-rest install 1.0.0
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-install', function ($request, $response) {
    //custom name of this package
    $name = 'cradlephp/cradle-rest';

    //get the current version
    $current = $this->package('global')->config('packages', $name);

    // if version is set
    if (is_array($current) && isset($current['version'])) {
        // get the current version
        $current = $current['version'];
    } else {
        $current = null;
    }

    //if it's already installed
    if ($current) {
        $message = sprintf('%s is already installed', $name);
        return $response->setError(true, $message);
    }

    // install package
    $version = $this->package('cradlephp/cradle-rest')->install('0.0.0');

    // update the config
    $this->package('global')->config('packages', $name, [
        'version' => $version,
        'active' => true
    ]);

    $response->setResults('version', $version);
});

/**
 * $ cradle package update cradlephp/cradle-rest
 * $ cradle package update cradlephp/cradle-rest 1.0.0
 * $ cradle cradlephp/cradle-rest update
 * $ cradle cradlephp/cradle-rest update 1.0.0
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-update', function ($request, $response) {
    //custom name of this package
    $name = 'cradlephp/cradle-rest';

    //get the current version
    $current = $this->package('global')->config('packages', $name);

    // if version is set
    if (is_array($current) && isset($current['version'])) {
        // get the current version
        $current = $current['version'];
    } else {
        $current = null;
    }

    //if it's not installed
    if (!$current) {
        $message = sprintf('%s is not installed', $name);
        return $response->setError(true, $message);
    }

    // get available version
    $version = $this->package($name)->version();

    //if available <= current
    if (version_compare($version, $current, '<=')) {
        $message = sprintf('%s %s <= %s', $name, $version, $current);
        return $response->setError(true, $message);
    }

    // update package
    $version = $this->package('cradlephp/cradle-rest')->install($current);

    // update the config
    $this->package('global')->config('packages', $name, [
        'version' => $version,
        'active' => true
    ]);

    $response->setResults('version', $version);
});

/**
 * $ cradle package remove cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest remove
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-remove', function ($request, $response) {
    //custom name of this package
    $name = 'cradlephp/cradle-rest';

    // if it's not installed
    if (!$this->package('global')->config('packages', $name)) {
        $message = sprintf('%s is not installed', $name);
        return $response->setError(true, $message);
    }

    //setup result counters
    $errors = [];

    // processed data
    $processed = [];

    //scan through each file
    foreach (scandir(__DIR__ . '/schema') as $file) {
        //if it's not a php file
        if(substr($file, -4) !== '.php') {
            //skip
            continue;
        }

        //get the schema data
        $data = include sprintf('%s/schema/%s', __DIR__, $file);

        //if no name
        if (!isset($data['name'])) {
            //skip
            continue;
        }

        //----------------------------//
        // 1. Prepare Data
        $request->setStage('schema', $data['name']);

        //----------------------------//
        // 2. Process Request
        $this->trigger('system-schema-remove', $request, $response);

        //----------------------------//
        // 3. Interpret Results
        if ($response->isError()) {
            //collect all the errors
            $errors[$data['name']] = $response->getMessage();
            continue;
        }

        $processed[] = $data['name'];
    }

    if (!empty($errors)) {
        $response->set('json', 'validation', $errors);
    }

    // get package config
    $packages = $this->package('global')->config('packages');

    // remove package from config
    if (isset($packages[$name])) {
        unset($packages[$name]);
    }

    // update package config
    $this->package('global')->config('packages', $packages);

    $response->setResults('schemas', $processed);
});

/**
 * $ cradle elastic flush cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest elastic-flush
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-elastic-flush', function ($request, $response) {
    $processed = $errors = [];
    //scan through each file
    foreach (scandir(__DIR__ . '/schema') as $file) {
        //if it's not a php file
        if(substr($file, -4) !== '.php') {
            //skip
            continue;
        }

        //get the schema data
        $data = include sprintf('%s/schema/%s', __DIR__, $file);

        // if name is not set
        if (!isset ($data['name'])) {
            // skip
            continue;
        }

        // set parameters
        $request->setStage('name', $data['name']);
        // trigger global schema flush
        $this->trigger('system-schema-flush-elastic', $request, $response);
        // intercept error
        if ($response->isError()) {
            //collect all the errors
            $errors[$data['name']] = $response->getMessage();
            continue;
        }


        $processed[] = $data['name'];
    }

    if (!empty($errors)) {
        $response->set('json', 'validation', $errors);
    }

    // set response
    $response->setResults('schema', $processed);
});

/**
 * $ cradle elastic map cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest elastic-map
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-elastic-map', function ($request, $response) {
    $processed = $errors = [];
    //scan through each file
    foreach (scandir(__DIR__ . '/schema') as $file) {
        //if it's not a php file
        if(substr($file, -4) !== '.php') {
            //skip
            continue;
        }

        //get the schema data
        $data = include sprintf('%s/schema/%s', __DIR__, $file);
        // if name is not set
        if (!isset ($data['name'])) {
            // skip
            continue;
        }

        // set parameters
        $request->setStage('name', $data['name']);
        // trigger global schema flush
        $this->trigger('system-schema-map-elastic', $request, $response);

        // intercept error
        if ($response->isError()) {
            //collect all the errors
            $errors[$data['name']] = $response->getMessage();
            continue;
        }

        $processed[] = $data['name'];
    }

    // set response error
    if (!empty ($errors)) {
        $response->set('json', 'validation', $errors);
    }

    $response->setResults('schema', $processed);
});

/**
 * $ cradle elastic populate cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest elastic-populate
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-elastic-populate', function ($request, $response) {
    $processed = $errors = [];
    //scan through each file
    foreach (scandir(__DIR__ . '/schema') as $file) {
        //if it's not a php file
        if(substr($file, -4) !== '.php') {
            //skip
            continue;
        }

        //get the schema data
        $data = include sprintf('%s/schema/%s', __DIR__, $file);
        // if name is not set
        if (!isset ($data['name'])) {
            // skip
            continue;
        }

        // set parameters
        $request->setStage('name', $data['name']);
        // trigger global schema flush
        $this->trigger('system-schema-populate-elastic', $request, $response);
        // intercept error
        if ($response->isError()) {
            $errors[$data['name']] = $response->getMessage();
            continue;
        }

        $processed[] = $data['name'];

    }

    // set response error
    if (!empty($errors)) {
        $response->set('json', 'validation', $errors);
    }

    // set response
    $response->setResults('schema', 'profile');
});

/**
 * $ cradle redis flush cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest redis-flush
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-redis-flush', function ($request, $response) {
    // initialize schema
    $schema = Schema::i('profile');
    // get redis service
    $redis = $schema->model()->service('redis');
    // remove cached search and detail from redis
    $redis->removeSearch();
    $redis->removeDetail();

    $response->setResults('schema', 'profile');
});

/**
 * $ cradle redis populate cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest redis-populate
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-redis-populate', function ($request, $response) {
    // initialize schema
    $schema = Schema::i('profile');
    // get sql service
    $sql = $schema->model()->service('sql');
    $redis = $schema->model()->service('redis');
    // get sql data
    $data = $sql->search();
    // if there is no results
    if (!isset($data['total']) && $data['total'] < 1) {
        // do not proceed
        return $response->setResults('schema', 'profile');
    }

    // get slugable fields
    $slugs = $schema->getSlugableFieldNames($schema->getPrimaryFieldName());
    // loop through rows
    foreach ($data['rows'] as $entry) {
        // loop thru slugs
        foreach ($slugs as $slug) {
            // if entry found
            if (isset($entry[$slug])) {
                // create cache data on redis
                $redis->createDetail($slug . '-' . $entry[$slug], $entry);
            }
        }

    }

    $response->setResults('schema', 'profile');

});

/**
 * $ cradle sql build cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest sql-build
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-sql-build', function ($request, $response) {
    //load up the database
    $pdo = $this->package('global')->service('sql-main');
    $database = SqlFactory::load($pdo);

    //setup result counters
    $errors = [];
    $processed = [];

    //scan through each file
    foreach (scandir(__DIR__ . '/schema') as $file) {
        //if it's not a php file
        if(substr($file, -4) !== '.php') {
            //skip
            continue;
        }

        //get the schema data
        $data = include sprintf('%s/schema/%s', __DIR__, $file);

        //if no name
        if (!isset($data['name'])) {
            //skip
            continue;
        }

        try {
            $schema = Schema::i($data['name']);
        } catch(Exception $e) {
            continue;
        }

        //remove primary table
        $database->query(sprintf('DROP TABLE IF EXISTS `%s`', $schema->getName()));

        //loop through relations
        foreach ($schema->getRelations() as $table => $relation) {
            //remove relation table
            $database->query(sprintf('DROP TABLE IF EXISTS `%s`', $table));
        }

        //now build it back up
        //set the data
        $request->setStage($schema->get());

        //----------------------------//
        // 1. Prepare Data
        //if detail has no value make it null
        if ($request->hasStage('detail')
            && !$request->getStage('detail')
        ) {
            $request->setStage('detail', null);
        }

        //if fields has no value make it an array
        if ($request->hasStage('fields')
            && !$request->getStage('fields')
        ) {
            $request->setStage('fields', []);
        }

        //if validation has no value make it an array
        if ($request->hasStage('validation')
            && !$request->getStage('validation')
        ) {
            $request->setStage('validation', []);
        }

        //----------------------------//
        // 2. Process Request
        //now trigger
        $this->trigger('system-schema-update', $request, $response);

        //----------------------------//
        // 3. Interpret Results
        //if the event returned an error
        if ($response->isError()) {
            //collect all the errors
            $errors[$data['name']] = $response->getValidation();
            continue;
        }

        $processed[] = $data['name'];
    }

    if (!empty($errors)) {
        $response->set('json', 'validation', $errors);
    }

    $response->setResults(['schemas' => $processed]);
});

/**
 * $ cradle sql flush cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest sql-flush
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-sql-flush', function ($request, $response) {
    //load up the database
    $pdo = $this->package('global')->service('sql-main');
    $database = SqlFactory::load($pdo);

    //setup result counters
    $errors = [];
    $processed = [];

    //scan through each file
    foreach (scandir(__DIR__ . '/schema') as $file) {
        //if it's not a php file
        if(substr($file, -4) !== '.php') {
            //skip
            continue;
        }

        //get the schema data
        $data = include sprintf('%s/schema/%s', __DIR__, $file);

        //if no name
        if (!isset($data['name'])) {
            //skip
            continue;
        }

        try {
            $schema = Schema::i($data['name']);
        } catch(Exception $e) {
            continue;
        }

        //remove primary table
        $database->query(sprintf('TRUNCATE `%s`', $schema->getName()));

        //loop through relations
        foreach ($schema->getRelations() as $table => $relation) {
            //remove relation table
            $database->query(sprintf('TRUNCATE `%s`', $table));
        }

        $processed[] = $data['name'];
    }

    $response->setResults('schemas', $processed);
});

/**
 * $ cradle sql populate cradlephp/cradle-rest
 * $ cradle cradlephp/cradle-rest sql-populate
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('cradlephp-cradle-rest-sql-populate', function ($request, $response) {
    //scan through each file
    foreach (scandir(__DIR__ . '/schema') as $file) {
        //if it's not a php file
        if(substr($file, -4) !== '.php') {
            //skip
            continue;
        }

        //get the schema data
        $data = include sprintf('%s/schema/%s', __DIR__, $file);

        //if no name
        if (!isset($data['name'], $data['fixtures'])
            || !is_array($data['fixtures'])
        ) {
            //skip
            continue;
        }

        $actionRequest = Request::i()->load();
        $actionResponse = Response::i()->load();
        foreach($data['fixtures'] as  $fixture) {
            $actionRequest
                ->setStage($fixture)
                ->setStage('schema', 'profile');

            $this->trigger('system-model-create', $actionRequest, $actionResponse);
        }
    }
});
