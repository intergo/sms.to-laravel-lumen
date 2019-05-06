<?php

if (!function_exists('smsto')) {
	function smsto()
	{
		return app(\Intergo\SmsTo\SmsTo::class);
	}
}
