<?php
class passerelleCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'passerelle';
	}
	public static function getRecordClassName ()
	{
		return 'passerelleRecord';
	}
}
