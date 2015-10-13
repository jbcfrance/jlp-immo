<?php
class agenceCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'agence';
	}
	public static function getRecordClassName ()
	{
		return 'agenceRecord';
	}
}
