<?php
class programmeCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'programme';
	}
	public static function getRecordClassName ()
	{
		return 'programmeRecord';
	}
}
