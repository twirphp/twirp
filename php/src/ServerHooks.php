<?php

namespace Twirp;

/**
 * ServerHooks is a container for callbacks that can instrument a
 * Twirp-generated server. These callbacks all accept a context and return a
 * context. They can use this to add to the request context as it threads
 * through the system, appending values or deadlines to it.
 *
 * The requestReceived and requestRouted hooks are special: they can return
 * errors (in form of exceptions).
 * If they trigger an error, handling for that request will be
 * stopped at that point. The Error hook will be triggered, and the error will
 * be sent to the client. This can be used for stuff like auth checks before
 * deserializing a request.
 *
 * The requestReceived hook is always called first, and it is called for every
 * request that the Twirp server handles. The last hook to be called in a
 * request's lifecycle is always responseSent, even in the case of an error.
 *
 * Details on the timing of each hook are documented as comments on the methods
 * of the ServerHooks type.
 */
interface ServerHooks
{
    /**
     * Called as soon as a request enters the Twirp
     * server at the earliest available moment.
     *
     * @throws \Throwable
     */
    public function requestReceived(array $ctx): array;

    /**
     * Called when a request has been routed to a
     * particular method of the Twirp server.
     *
     * @throws \Throwable
     */
    public function requestRouted(array $ctx): array;

    /**
     * Called when a request has been handled and a
     * response is ready to be sent to the client.
     *
     * @throws \Throwable
     */
    public function responsePrepared(array $ctx): array;

    /**
     * Called when all bytes of a response (including an error
     * response) have been returned. Because the responseSent hook is terminal, it
     * does not return a context.
     *
     * For the same reason, it MUST NOT throw any exceptions.
     */
    public function responseSent(array $ctx): void;

    /**
     * Called when an error occurs while handling a request. The
     * Error is passed as argument to the hook.
     */
    public function error(array $ctx, \Throwable $error): array;
}
