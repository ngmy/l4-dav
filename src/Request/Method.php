<?php

declare(strict_types=1);

namespace Ngmy\WebDav\Request;

use Ngmy\Enum\Enum;

/**
 * @method static self GET()
 * @method static self PUT()
 * @method static self DELETE()
 * @method static self HEAD()
 * @method static self COPY()
 * @method static self MOVE()
 * @method static self MKCOL()
 * @method static self PROPFIND()
 * @method static self PROPPATCH()
 */
class Method extends Enum
{
    /**
     * @var string
     * @enum
     */
    private static $GET;
    /**
     * @var string
     * @enum
     */
    private static $PUT;
    /**
     * @var string
     * @enum
     */
    private static $DELETE;
    /**
     * @var string
     * @enum
     */
    private static $HEAD;
    /**
     * @var string
     * @enum
     */
    private static $COPY;
    /**
     * @var string
     * @enum
     */
    private static $MOVE;
    /**
     * @var string
     * @enum
     */
    private static $MKCOL;
    /**
     * @var string
     * @enum
     */
    private static $PROPFIND;
    /**
     * @var string
     * @enum
     */
    private static $PROPPATCH;
}
