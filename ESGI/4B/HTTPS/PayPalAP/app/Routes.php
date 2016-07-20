<?php
/**
 * Routes - all standard Routes are defined here.
 *
 * @author Scarpa Team
 * @version 3.0
 */

use Helpers\Hooks;


/** Define static routes. */

// Default Routing
Router::any('', 'App\Controllers\Subscription@index');
Router::any('subscribe', 'App\Controllers\Subscription@subscribe');

//Paypal API routes
Router::any('paypal/pay', 'App\Controllers\Paypal@pay');
Router::any('paypal/refund', 'App\Controllers\Paypal@refund');

/** End static Routes */

/** Module Routes. */
$hooks = Hooks::get();

$hooks->run('routes');
/** End Module Routes. */

