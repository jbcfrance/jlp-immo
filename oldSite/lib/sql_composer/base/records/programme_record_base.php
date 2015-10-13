<?php
class programmeRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'programme';
	}
	public static function getCollectionClassName ()
	{
		return 'programmeCollection';
	}
}
