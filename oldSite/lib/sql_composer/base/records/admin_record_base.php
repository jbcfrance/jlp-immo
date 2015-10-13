<?php
class adminRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'admin';
	}
	public static function getCollectionClassName ()
	{
		return 'adminCollection';
	}
}
