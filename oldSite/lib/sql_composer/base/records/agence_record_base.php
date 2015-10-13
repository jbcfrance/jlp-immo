<?php
class agenceRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'agence';
	}
	public static function getCollectionClassName ()
	{
		return 'agenceCollection';
	}
}
