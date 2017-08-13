<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */

// +----------------------------------------------------------------------+

// | PHP version 5                                                        |

// +----------------------------------------------------------------------+

// | Copyright (c) 1997-2004 The PHP Group                                |

// +----------------------------------------------------------------------+

// | This source file is subject to version 3.0 of the PHP license,       |

// | that is bundled with this package in the file LICENSE, and is        |

// | available through the world-wide-web at the following url:           |

// | http://www.php.net/license/3_0.txt.                                  |

// | If you did not receive a copy of the PHP license and are unable to   |

// | obtain it through the world-wide-web, please send a note to          |

// | license@php.net so we can mail you a copy immediately.               |

// +----------------------------------------------------------------------+

// | Authors: Original Author <author@example.com>                        |

// |          Your Name <you@example.com>                                 |

// +----------------------------------------------------------------------+

//

// $Id:$



class url {

    static function baseUrl($uri = '') {

        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';

        $baseUrl.= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');

        $baseUrl.= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));

        $baseUrl = str_replace('/public', '', $baseUrl);

        //echo $base_url.$uri;
		//echo "alsdkjf = ".$base_url;
		//exit();
        return $baseUrl . $uri;

    }

}

?>

