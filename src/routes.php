<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

$container = $app->getContainer();

// Default Instantiate the renderer
//$container['renderer'] = new PhpRenderer("./templates"); // already declared invsrc/dependencies

/* twig-view */
// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('./views', [
        'cache' => false //'logs/cache'
    ]);
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};
/* twig-view */


$app->get('/[{name:.*}]', function ($request, $response, $args) {
    $name = isset($args['name']) ? $args['name'] : '';
    $urlParts = explode('/', $name);

    if ($name === '') {

        //return $this->renderer->render($response, 'index.phtml', $args); // Default Render view
        return $this->view->render($response, 'home.phtml', $args); // Twig Render view

    } elseif ($name === 'testPage') {

        $data = array(
            'heading' => 'About page',
            'message' => 'This page is an example of a static route, rendering a PHP file.'
        );
        return $this->renderer->render($response, 'testpage.php', $data);

    } elseif (count($urlParts)>1) {

        // Access the query strings
        $qs = $request->getQueryParams();
        $nodeName = $urlParts[0];
        if(isset($qs['id'])){ $nodeId = $qs['id']; }
        if(isset($urlParts[2]) && is_numeric($urlParts[2])){ $nodeId = $urlParts[2]; }
        $out = (($urlParts[1]=='view') && isset($nodeId)) ? getNode($nodeName,$nodeId,$qs) : getNodes($nodeName,$qs);
        return $this->renderer->render($response, 'apinode.php', $out);

    } else {
        // Render the corresponding view file
        $viewFilename = $name . '.phtml';
        //return $this->renderer->render($response, $viewFilename, $args); // Default Render view
        return $this->view->render($response, $viewFilename, $args); // Twig Render view
    }
});

$app->post('/processform', function ($request, $response, $args) {
    $baseUrl = $request->getUri()->getBaseUrl();

    // Access the POST variables
    $postData = $request->getParsedBody();

    // Process the form data

    // Assuming form processing is successful
    $success = false;

    if ($success) {
        // Redirect to /thankyou page
        return $response->withRedirect( $baseUrl . '/thankyou');
    } else {
        // Redirect back to homepage
        return $response->withRedirect( $baseUrl . '/');
    }
    // eof Process the form data

});

function getNodes($request, $args)
{
    $url = getenv('APIURL') . $request . '.json';
    // Retrieve all Nodes from the database or data source
    $getData = curlGetRequest($url);
    // Return the Nodes as Array response
    $out = json_decode($getData, true);
    //print_r($out); exit;
    return $out;
}

function getNode($request, $id, $args)
{
    $url = getenv('APIURL') . $request . '/view/' . $id . '.json';
    // Retrieve the Node with the provided ID from the database or data source
    $getData = curlGetRequest($url);
    // Return the Node as Array response
    $out = json_decode($getData, true);
    return $out;
}

function createNode($request, $args)
{
    // Retrieve the Node data from the request body
    $postData = $request->getParsedBody();

    // Create a new Node in the database or data source
    // Return the newly created Node as Array response
}

function updateNode($request, $id, $args)
{
    // Retrieve the Node data from the request body
    $postData = $request->getParsedBody();

    // Update the Node with the provided ID in the database or data source
    // Return the updated Node as Array response
}

function deleteNode($request, $id, $args)
{
    // Delete the Node with the provided ID from the database or data source
    // Return a success message or appropriate response
}

function curlGetRequest($url)
{
    $curl = curl_init($url);

    // Set cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // Add any additional cURL options as needed
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'app:'.getenv('APPKEY'), 'hash:'.getenv('APPHASH') ]);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    // Execute the request
    $response = curl_exec($curl);

    // Close cURL resource
    curl_close($curl);

    return $response;
}

function curlPostRequest($url, $data)
{
    $curl = curl_init($url);

    // Set cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    // Add any additional cURL options as needed
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'app:'.getenv('APPKEY'), 'hash:'.getenv('APPHASH') ]);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    // Execute the request
    $response = curl_exec($curl);

    // Close cURL resource
    curl_close($curl);

    return $response;
}
