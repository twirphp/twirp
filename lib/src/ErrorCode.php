<?php

declare(strict_types=1);

namespace Twirp;

/**
 * Valid Twirp error types. Most error types are equivalent to gRPC status codes
 * and follow the same semantics.
 */
final class ErrorCode
{
    // Canceled indicates the operation was cancelled (typically by the caller).
    public const Canceled = 'canceled';

    // Unknown error. For example when handling errors raised by APIs that do not
    // return enough error information.
    public const Unknown = 'unknown';

    // InvalidArgument indicates client specified an invalid argument. It
    // indicates arguments that are problematic regardless of the state of the
    // system (i.e. a malformed file name, required argument, number out of range,
    // etc.).
    public const InvalidArgument = 'invalid_argument';

    // DeadlineExceeded means operation expired before completion. For operations
    // that change the state of the system, this error may be returned even if the
    // operation has completed successfully (timeout).
    public const DeadlineExceeded = 'deadline_exceeded';

    // NotFound means some requested entity was not found.
    public const NotFound = 'not_found';

    // BadRoute means that the requested URL path wasn't routable to a Twirp
    // service and method. This is returned by the generated server, and usually
    // shouldn't be returned by applications. Instead, applications should use
    // NotFound or Unimplemented.
    public const BadRoute = 'bad_route';

    // AlreadyExists means an attempt to create an entity failed because one
    // already exists.
    public const AlreadyExists = 'already_exists';

    // PermissionDenied indicates the caller does not have permission to execute
    // the specified operation. It must not be used if the caller cannot be
    // identified (Unauthenticated).
    public const PermissionDenied = 'permission_denied';

    // Unauthenticated indicates the request does not have valid authentication
    // credentials for the operation.
    public const Unauthenticated = 'unauthenticated';

    // ResourceExhausted indicates some resource has been exhausted, perhaps a
    // per-user quota, or perhaps the entire file system is out of space.
    public const ResourceExhausted = 'resource_exhausted';

    // FailedPrecondition indicates operation was rejected because the system is
    // not in a state required for the operation's execution. For example, doing
    // an rmdir operation on a directory that is non-empty, or on a non-directory
    // object, or when having conflicting read-modify-write on the same resource.
    public const FailedPrecondition = 'failed_precondition';

    // Aborted indicates the operation was aborted, typically due to a concurrency
    // issue like sequencer check failures, transaction aborts, etc.
    public const Aborted = 'aborted';

    // OutOfRange means operation was attempted past the valid range. For example,
    // seeking or reading past end of a paginated collection.
    //
    // Unlike InvalidArgument, this error indicates a problem that may be fixed if
    // the system state changes (i.e. adding more items to the collection).
    //
    // There is a fair bit of overlap between FailedPrecondition and OutOfRange.
    // We recommend using OutOfRange (the more specific error) when it applies so
    // that callers who are iterating through a space can easily look for an
    // OutOfRange error to detect when they are done.
    public const OutOfRange = 'out_of_range';

    // Unimplemented indicates operation is not implemented or not
    // supported/enabled in this service.
    public const Unimplemented = 'unimplemented';

    // Internal errors. When some invariants expected by the underlying system
    // have been broken. In other words, something bad happened in the library or
    // backend service. Do not confuse with HTTP Internal Server Error; an
    // Internal error could also happen on the client code, i.e. when parsing a
    // server response.
    public const Internal = 'internal';

    // Unavailable indicates the service is currently unavailable. This is a most
    // likely a transient condition and may be corrected by retrying with a
    // backoff.
    public const Unavailable = 'unavailable';

    // DataLoss indicates unrecoverable data loss or corruption.
    public const DataLoss = 'data_loss';

    // NoError is the zero-value, is considered an empty error and should not be
    // used.
    public const NoError = '';

    /**
     * Maps a Twirp error type into a similar HTTP
     * response status. It is used by the Twirp server handler to set the HTTP
     * response status code. Returns 0 if the error code is invalid.
     */
    public static function serverHTTPStatusFromErrorCode(string $code): int
    {
        switch ($code) {
            case self::Canceled:
                return 408; // RequestTimeout
            case self::Unknown:
                return 500; // Internal Server Error
            case self::InvalidArgument:
                return 400; // BadRequest
            case self::DeadlineExceeded:
                return 408; // RequestTimeout
            case self::NotFound:
                return 404; // Not Found
            case self::BadRoute:
                return 404; // Not Found
            case self::AlreadyExists:
                return 409; // Conflict
            case self::PermissionDenied:
                return 403; // Forbidden
            case self::Unauthenticated:
                return 401; // Unauthorized
            case self::ResourceExhausted:
                return 429; // Too Many Requests
            case self::FailedPrecondition:
                return 412; // Precondition Failed
            case self::Aborted:
                return 409; // Conflict
            case self::OutOfRange:
                return 400; // Bad Request
            case self::Unimplemented:
                return 501; // Not Implemented
            case self::Internal:
                return 500; // Internal Server Error
            case self::Unavailable:
                return 503; // Service Unavailable
            case self::DataLoss:
                return 500; // Internal Server Error
            case self::NoError:
                return 200; // OK
            default:
                return 0; // Invalid!
        }
    }

    /**
     * Returns true if is one of the valid predefined constants.
     */
    public static function isValid(string $code): bool
    {
        return self::serverHTTPStatusFromErrorCode($code) != 0;
    }
}
