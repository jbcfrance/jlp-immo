<?php
class negociateurCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'negociateur';
	}
	public static function getRecordClassName ()
	{
		return 'negociateurRecord';
	}
}
