<?php

class XenForo_DataWriter_Helper_Denormalization
{
	public static function verifyIntCommaList(&$list, XenForo_DataWriter $dw, $fieldName = false)
	{
		if ($list === '')
		{
			return true;
		}

		$items = explode(',', $list);
		$items = array_map('intval', $items);
		$listNew = implode(',', $items);
		if ($list === $listNew)
		{
			return true;
		}

		// debugging message, no need for phrasing
		$dw->error("Please provide a list of values separated by commas only.", $fieldName);
		return false;
	}

	public static function verifySerialized(&$serial, XenForo_DataWriter $dw, $fieldName = false)
	{
		if (!is_string($serial))
		{
			$serial = serialize($serial);
			$verifyValidSerialization = false;
		}
		else
		{
			// already serialized, so we need to check whether this is valid
			$verifyValidSerialization = true;
		}

		if (XenForo_Helper_Php::serializedContainsObject($serial))
		{
			throw new XenForo_Exception("Serialized value contains an object and this is not allowed");
		}

		if ($verifyValidSerialization)
		{
			if (@unserialize($serial) === false && $serial != serialize(false))
			{
				$dw->error('The data provided as a serialized array does not unserialize.', $fieldName);
				return false;
			}
		}

		return true;
	}
}