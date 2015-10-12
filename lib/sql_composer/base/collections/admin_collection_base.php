<?php
class adminCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'admin';
	}
	public static function getRecordClassName ()
	{
		return 'adminRecord';
	}
}
