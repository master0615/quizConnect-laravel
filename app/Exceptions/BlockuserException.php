<?php

namespace App\Exceptions;

use Exception;

class BlockUserException extends Exception
{
    public function render($request) {
		return response()->json([
			'message' => 'Your account is blocked. Please contact us to re-activate it.',
		], 401);
	}
}
