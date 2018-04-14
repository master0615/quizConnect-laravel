<?php

namespace App\Exceptions;

use Exception;

class InvalidAccessException extends Exception
{
    public function render($request) {
		return response()->json([
			'message' => 'The user can not access this form.',
		], 401);
	}
}