<?php
class siteRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'site';
	}
	public static function getCollectionClassName ()
	{
		return 'siteCollection';
	}
}
