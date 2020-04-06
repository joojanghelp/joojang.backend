<?php
namespace App\Exceptions;

use Exception;

/**
 * 기타 Exception 처리.
 *
 * Class AdminAuthException
 * @package App\Exceptions
 */
class AdminAuthException extends Exception
{
	/**
	 * Report the exception.
	 *
	 * @return void
	 */
	public function report()
	{

	}

	/**
	 * Render the exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, $exception)
	{
		return response()->json([
			'error_message' => __('auth.need_admin_level'),
		], 403);
	}
}
