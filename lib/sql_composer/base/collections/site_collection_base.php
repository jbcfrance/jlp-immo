<?php
class siteCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'site';
	}
	public static function getRecordClassName ()
	{
		return 'siteRecord';
	}
}
