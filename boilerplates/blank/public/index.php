<?php

/*
                                 .+syyso/.
                                odddhhhhhhy/`
                               oddhhhyyyyyyyy+`
                              :dhhyyyyyyssssyys:
                              yhhyyyyyysssssssss/
                             `hhyyyyysssssssossso-
                             -hhyyysssssssooooooo+
                             .yyyysssssssoooooooo/
                              syyysssssoooooooo++-
                              .sssssssooooooo+++:
                               `/sssooooooo+++/.
                                  .:/++o++/:-`






           `ss:/sys+``+syso-   oso   `/syhys/  -ss-+yhyo` .+syys+.
           .NNNmssmNmNNyshNNs  mNd  +NNmsosds` :NNNdyshs`sNNhsohNNy`
           .NNh   `mNN-   sNN. mNd .NNy        :NNs     oNN/    :NNy
           .NNs    hNN    +NN- mNd :NNo        :NN+     sNN.    `NNh
           .NNs    hNN    +NN- mNd  hNNo:--+/  :NN+     .mNd/--/dNN:
           .NNs    yNm    +NN- dNh   /hNNNNNh: :NN/      `odNNNNdo.
                                        ```                  ``

*/

namespace µ;

/**
 * Starting timestamp for “performance” monitoring.
 */
$start = microtime(true);


/**
 * Use Composer’s autoloading. This is mandatory as µ doesn’t work without it
 * as well as you won’t be able to access any of its functionality.
 *
 * @see https://getcomposer.org/doc/01-basic-usage.md#autoloading
 */
require __DIR__ . '/../vendor/autoload.php';


/**
 * Load a configuration file. Currently supported file types are .php, .ini, .json and .yaml.
 */
config()->append('../configs/dev.yaml');


/**
 * Add global data/functions that should be available in all templates.
 */
template()->addData([
    'paths' => (object) config()->get('paths'),
    'pageload' => function () use ($start) {
        return number_format((microtime(true) - $start) * 1000, 2) . 'ms';
    }
]);


/**
 * Register routes…
 */
router()->get('/', function () {
    echo template()->render('index');
});


/**
 * Dispatch the request and retrieve the response status code.
 */
list($statusCode) = router()->dispatch();


/**
 * A really basic handler function for 404 errors. Can be used anywhere
 * in any context. For example if a given route was matched but the
 * provided data was malicious.
 *
 * @param array $data Additional template data.
 */
function handle404(array $data = [])
{
    http_response_code(404);

    echo template()->render('404', $data);
}


if ($statusCode === 404) {
    handle404();
}
