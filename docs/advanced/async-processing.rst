Async Processing
================

This document describes how to use asynchronous processing with Twirp PHP clients using Guzzle's promise framework.

Overview
--------

All generated Twirp PHP clients now support asynchronous request processing using promises that conform to the PSR-7 standard and leverage Guzzle's promise implementation. This allows you to make non-blocking HTTP requests and handle multiple requests concurrently.

Requirements
------------

* **Guzzle HTTP Client** (^7.0): For async support, you must use Guzzle as your HTTP client
* **guzzlehttp/promises** (^1.5 or ^2.0): Promise implementation (usually installed as a Guzzle dependency)

Installation
------------

If not already installed, add Guzzle to your project:

.. code-block:: bash

   composer require guzzlehttp/guzzle

Basic Usage
-----------

Synchronous Request (Existing Behavior)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

   use GuzzleHttp\Client as GuzzleClient;
   use YourNamespace\GreeterClient;
   use YourNamespace\HelloRequest;

   // Create a Guzzle HTTP client
   $httpClient = new GuzzleClient();

   // Create the Twirp client
   $client = new GreeterClient('http://localhost:8080', $httpClient);

   // Make a synchronous request
   $request = new HelloRequest();
   $request->setName('World');

   try {
       $response = $client->SayHello([], $request);
       echo $response->getMessage();
   } catch (\Twirp\Error $e) {
       echo "Error: " . $e->getMessage();
   }

Asynchronous Request (New Feature)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

   use GuzzleHttp\Client as GuzzleClient;
   use YourNamespace\GreeterClient;
   use YourNamespace\HelloRequest;

   // Create a Guzzle HTTP client
   $httpClient = new GuzzleClient();

   // Create the Twirp client
   $client = new GreeterClient('http://localhost:8080', $httpClient);

   // Make an asynchronous request
   $request = new HelloRequest();
   $request->setName('World');

   // Returns a promise immediately
   $promise = $client->SayHelloAsync([], $request);

   // Handle the response when it's ready
   $promise->then(
       function ($response) {
           echo "Success: " . $response->getMessage();
       },
       function ($exception) {
           echo "Error: " . $exception->getMessage();
       }
   );

   // Wait for the promise to resolve (optional)
   $promise->wait();

Advanced Usage
--------------

Multiple Concurrent Requests
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can make multiple asynchronous requests and wait for all of them to complete:

.. code-block:: php

   use GuzzleHttp\Client as GuzzleClient;
   use GuzzleHttp\Promise;
   use YourNamespace\GreeterClient;
   use YourNamespace\HelloRequest;

   $httpClient = new GuzzleClient();
   $client = new GreeterClient('http://localhost:8080', $httpClient);

   // Create multiple requests
   $promises = [];

   foreach (['Alice', 'Bob', 'Charlie'] as $name) {
       $request = new HelloRequest();
       $request->setName($name);
       $promises[$name] = $client->SayHelloAsync([], $request);
   }

   // Wait for all promises to complete
   $results = Promise\Utils::settle($promises)->wait();

   // Process results
   foreach ($results as $name => $result) {
       if ($result['state'] === 'fulfilled') {
           echo "$name: " . $result['value']->getMessage() . "\n";
       } else {
           echo "$name failed: " . $result['reason']->getMessage() . "\n";
       }
   }

Using Promise::all()
^^^^^^^^^^^^^^^^^^^^

If you want all requests to succeed or fail together:

.. code-block:: php

   use GuzzleHttp\Promise;

   $promises = [
       'alice' => $client->SayHelloAsync([], $request1),
       'bob' => $client->SayHelloAsync([], $request2),
       'charlie' => $client->SayHelloAsync([], $request3),
   ];

   // Wait for all promises - throws if any fails
   try {
       $results = Promise\Utils::all($promises)->wait();
       foreach ($results as $name => $response) {
           echo "$name: " . $response->getMessage() . "\n";
       }
   } catch (\Exception $e) {
       echo "One or more requests failed: " . $e->getMessage();
   }

Chaining Promises
^^^^^^^^^^^^^^^^^

You can chain multiple operations:

.. code-block:: php

   $client->SayHelloAsync([], $request)
       ->then(function ($response) use ($client) {
           // Use the response to make another request
           $followUpRequest = new AnotherRequest();
           $followUpRequest->setData($response->getMessage());
           return $client->AnotherMethodAsync([], $followUpRequest);
       })
       ->then(function ($finalResponse) {
           echo "Final result: " . $finalResponse->getResult();
       })
       ->otherwise(function ($exception) {
           echo "Error in chain: " . $exception->getMessage();
       })
       ->wait();

Non-blocking Execution
^^^^^^^^^^^^^^^^^^^^^^

For truly non-blocking execution, don't call ``wait()``:

.. code-block:: php

   // Fire off async requests without waiting
   $promise1 = $client->Method1Async([], $request1);
   $promise2 = $client->Method2Async([], $request2);

   // Do other work here
   doOtherWork();

   // Later, check if promises are resolved
   if ($promise1->getState() === 'fulfilled') {
       $result = $promise1->wait(); // Returns immediately since already resolved
   }

Fallback Behavior
-----------------

If you don't use Guzzle as your HTTP client (e.g., using a generic PSR-18 client), the async methods will automatically fall back to synchronous execution and return a resolved promise. This ensures backward compatibility.

.. code-block:: php

   use Symfony\Component\HttpClient\Psr18Client;

   // Non-Guzzle client
   $httpClient = new Psr18Client();
   $client = new GreeterClient('http://localhost:8080', $httpClient);

   // This will execute synchronously but still return a promise
   $promise = $client->SayHelloAsync([], $request);
   $response = $promise->wait();

Error Handling
--------------

Async methods throw the same ``\Twirp\Error`` exceptions as synchronous methods, but they're caught in the promise rejection handler:

.. code-block:: php

   $client->SayHelloAsync([], $request)
       ->then(
           function ($response) {
               // Success handler
               return $response;
           },
           function ($error) {
               // Error handler
               if ($error instanceof \Twirp\Error) {
                   echo "Twirp Error Code: " . $error->getErrorCode() . "\n";
                   echo "Message: " . $error->getMessage() . "\n";
                   
                   // Check metadata
                   $metadata = $error->getMeta();
                   if (isset($metadata['http_error_from_intermediary'])) {
                       echo "HTTP intermediary error\n";
                   }
               }
               throw $error; // Re-throw if you want to propagate
           }
       );

Best Practices
--------------

1. **Use Guzzle for async operations**: While other PSR-18 clients work, only Guzzle supports true async processing.

2. **Handle errors appropriately**: Always provide rejection handlers for your promises to catch errors.

3. **Don't call wait() in loops**: If making many requests, collect all promises first, then use ``Promise\Utils::settle()`` or ``Promise\Utils::all()``.

4. **Consider connection pooling**: Guzzle reuses connections by default, which is more efficient for multiple requests.

5. **Set appropriate timeouts**: Configure Guzzle with appropriate timeouts for async operations:

.. code-block:: php

   $httpClient = new GuzzleClient([
       'timeout' => 10.0,
       'connect_timeout' => 5.0,
   ]);

JSON Client Support
-------------------

Both the Protobuf client and JSON client support async operations:

.. code-block:: php

   use YourNamespace\GreeterJsonClient;

   $jsonClient = new GreeterJsonClient('http://localhost:8080', $httpClient);
   $promise = $jsonClient->SayHelloAsync([], $request);

Performance Considerations
--------------------------

Async requests provide the most benefit when:

* Making multiple independent requests that can run concurrently
* Dealing with high-latency services
* Building services that need to remain responsive while waiting for I/O

For single requests with no other work to do, synchronous requests may be simpler and sufficient.

More Information
----------------

* `Guzzle Promises Documentation <https://github.com/guzzle/promises>`_
* `Guzzle Async Requests <https://docs.guzzlephp.org/en/stable/quickstart.html#async-requests>`_
* `PSR-7: HTTP Message Interface <https://www.php-fig.org/psr/psr-7/>`_

