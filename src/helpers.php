<?php

if (!function_exists('smsto')) {
	function smsto()
	{
		return app(\SmsTo::class);
	}
}
