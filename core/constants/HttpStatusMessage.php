<?php
    namespace Core\Constants;
    /** HTTP status codes */
    abstract class HttpStatusMessage
    {
        const __default = self::OK;
        const SWITCHING_PROTOCOLS             = 'Switching Protocols';
        const OK                              = 'OK';
        const CREATED                         = 'Created';
        const ACCEPTED                        = 'Accepted';
        const NONAUTHORITATIVE_INFORMATION    = 'Non-Authoritative Information';
        const NO_CONTENT                      = 'No Content';
        const RESET_CONTENT                   = 'Reset Content';
        const PARTIAL_CONTENT                 = 'Partial Content';
        const MULTIPLE_CHOICES                = 'Multiple Choices';
        const MOVED_PERMANENTLY               = 'Moved Permanently';
        const MOVED_TEMPORARILY               = 'Moved Temporarily';
        const SEE_OTHER                       = 'See Other';
        const NOT_MODIFIED                    = 'Not Modified';
        const USE_PROXY                       = 'Use Proxy';
        const BAD_REQUEST                     = 'Bad Request';
        const UNAUTHORIZED                    = 'Unauthorized';
        const PAYMENT_REQUIRED                = 'Payment Required';
        const FORBIDDEN                       = 'Forbidden';
        const NOT_FOUND                       = 'Not Found';
        const METHOD_NOT_ALLOWED              = 'Method Not Allowed';
        const NOT_ACCEPTABLE                  = 'Not Acceptable';
        const PROXY_AUTHENTICATION_REQUIRED   = 'Proxy Authentication Required';
        const REQUEST_TIMEOUT                 = 'Request Time-out';
        const CONFLICT                        = 'Conflict';
        const GONE                            = 'Gone';
        const LENGTH_REQUIRED                 = 'Length Required';
        const PRECONDITION_FAILED             = 'Precondition Failed';
        const REQUEST_ENTITY_TOO_LARGE        = 'Request Entity Too Large';
        const REQUESTURI_TOO_LARGE            = 'Request-URI Too Large';
        const UNSUPPORTED_MEDIA_TYPE          = 'Unsupported Media Type';
        const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
        const EXPECTATION_FAILED              = 417;
        const IM_A_TEAPOT                     = 'I\'M a Teapot';
        const INTERNAL_SERVER_ERROR           = 'Internal Server Error';
        const NOT_IMPLEMENTED                 = 'Not Implemented';
        const BAD_GATEWAY                     = 'Bad Gateway';
        const SERVICE_UNAVAILABLE             = 'Service Unavailable';
        const GATEWAY_TIMEOUT                 = 'Gateway Time-out';
        const HTTP_VERSION_NOT_SUPPORTED      = 'HTTP Version not supported';
    }