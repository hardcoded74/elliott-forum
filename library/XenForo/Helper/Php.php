<?php

class XenForo_Helper_Php
{
	/**
	 * Validates a callback more strictly and with more detailed errors.
	 *
	 * @param string|object|array $class A class name, object, function name, or array containing class/object and method
	 * @param null|string $method If first param is class or object, the method name
	 * @param string $error Error key returned by reference
	 * @param bool $forceMethod If true, if no method is provided, never treat the class as a function
	 *
	 * @return bool
	 *
	 * @throws InvalidArgumentException
	 */
	public static function validateCallback($class, $method = null, &$error = null, $forceMethod = true)
	{
		if (is_array($class))
		{
			if ($method)
			{
				throw new InvalidArgumentException('Method cannot be provided with class as array');
			}

			$method = $class[1];
			$class = $class[0];
		}

		if ($forceMethod)
		{
			$method = strval($method);
		}
		else
		{
			if (!$method)
			{
				if (is_object($class))
				{
					throw new InvalidArgumentException('Object given with no method');
				}

				if (!function_exists($class))
				{
					$error = 'invalid_function';
					return false;
				}
				else
				{
					return true;
				}
			}
		}

		if (!is_string($method))
		{
			throw new InvalidArgumentException('Method to check is not a string');
		}

		if (!is_object($class))
		{
			if (!$class || !class_exists($class))
			{
				$error = 'invalid_class';
				return false;
			}
		}

		$reflectionClass = new ReflectionClass($class);
		$isObject = is_object($class);

		if (
			($isObject && $reflectionClass->hasMethod('__call'))
			|| (!$isObject && $reflectionClass->hasMethod('__callStatic'))
		)
		{
			// magic method will always be called if a method can't be
			return true;
		}

		if (!$method || !$reflectionClass->hasMethod($method))
		{
			$error = 'invalid_method';
			return false;
		}

		$reflectionMethod = $reflectionClass->getMethod($method);

		if ($reflectionMethod->isAbstract() || !$reflectionMethod->isPublic())
		{
			$error = 'invalid_method_configuration';
			return false;
		}

		$isStatic = $reflectionMethod->isStatic();

		if ($isStatic && $isObject)
		{
			$error = 'method_static';
			return false;
		}
		else if (!$isStatic && !$isObject)
		{
			$error = 'method_not_static';
			return false;
		}

		return true;
	}

	/**
	 * Does a detailed validation of a callback and returns the error
	 * in a ready to print phrase
	 *
	 * @param string|object|array $class A class name, object, function name, or array containing class/object and method
	 * @param null|string $method If first param is class or object, the method name
	 * @param null|XenForo_Phrase $errorPhrase If an error occurs, outputs the phrase
	 * @param bool $forceMethod If true, if no method is provided, never treat the class as a function
	 *
	 * @return bool
	 */
	public static function validateCallbackPhrased($class, $method = null, &$errorPhrase = null, $forceMethod = true)
	{
		$success = self::validateCallback($class, $method, $error, $forceMethod);
		if ($success)
		{
			return true;
		}

		$printableCallback = self::getPrintableCallback($class, $method);
		$innerErrorPhrase = new XenForo_Phrase('error_' . $error);

		$errorPhrase = new XenForo_Phrase('callback_x_invalid_y', array(
			'callback' => $printableCallback,
			'error' => $innerErrorPhrase
		));

		return false;
	}

	/**
	 * Returns a callback in a simple printable form
	 *
	 * @param string|object|array $class A class name, object, function name, or array containing class/object and method
	 * @param null|string $method If first param is class or object, the method name
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException
	 */
	public static function getPrintableCallback($class, $method = null)
	{
		if (is_array($class))
		{
			if ($method)
			{
				throw new InvalidArgumentException('Method cannot be provided with class as array');
			}

			$method = $class[1];
			$class = $class[0];
		}

		if (!$method)
		{
			if (is_object($class))
			{
				throw new InvalidArgumentException('Object given with no method');
			}

			return strval($class);
		}

		if (!is_string($method))
		{
			throw new InvalidArgumentException('Method must be a string when given an object');
		}

		if (is_object($class))
		{
			return get_class($class) . '->' . $method;
		}
		else
		{
			return $class . '::' . $method;
		}
	}

	/**
	 * Unserializes a string, avoiding unserializing potentially dangerous objects.
	 *
	 * In PHP7, if an object is present, unserialization will happen but with the object becoming in complete.
	 * In previous versions, if an object is present, unserialization will fail and false will be returned.
	 *
	 * See serializedContainsObject for comments on false positives.
	 *
	 * @param string $serialized
	 *
	 * @return bool|mixed
	 */
	public static function safeUnserialize($serialized)
	{
		if (PHP_VERSION_ID >= 70000)
		{
			// PHP 7 has an option to disable unserializing objects, so use that if available
			return @unserialize($serialized, array('allowed_classes' => false));
		}

		if (self::serializedContainsObject($serialized))
		{
			return false;
		}

		return @unserialize($serialized);
	}

	/**
	 * Serializes a string only if it doesn't contain object constructs. This can be paired with safeUnserialize
	 * to block the serialization if unserialization will fail anyway. (Serialization itself is safe, but if it's going
	 * to fail to unserialize, it likely shouldn't be allowed.)
	 *
	 * See serializedContainsObject for comments on false positives.
	 *
	 * @param string $toSerialize
	 *
	 * @return string
	 */
	public static function safeSerialize($toSerialize)
	{
		$serialized = serialize($toSerialize);

		if (self::serializedContainsObject($serialized))
		{
			throw new InvalidArgumentException("Serialized value contains an object and this is not allowed");
		}

		return $serialized;
	}

	/**
	 * This detects if a serialized string may contain an object definition.
	 * This can trigger a false positive if a string matches the format but it should be unlikely.
	 *
	 * This function could be implemented with a single, one-line regex but it has been optimized, particularly
	 * for the case that no object or object-like construct is present.
	 *
	 * @param string $serialized
	 *
	 * @return bool
	 */
	public static function serializedContainsObject($serialized)
	{
		if (strpos($serialized, 'O:') !== false && preg_match('#(?<=^|[;{}])O:[+-]?[0-9]+:"#', $serialized))
		{
			return true;
		}

		if (strpos($serialized, 'C:') !== false && preg_match('#(?<=^|[;{}])C:[+-]?[0-9]+:"#', $serialized))
		{
			return true;
		}

		if (strpos($serialized, 'o:') !== false && preg_match('#(?<=^|[;{}])o:[+-]?[0-9]+:"#', $serialized))
		{
			return true;
		}

		return false;
	}
}