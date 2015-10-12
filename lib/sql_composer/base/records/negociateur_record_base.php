<?php
class negociateurRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'negociateur';
	}
	public static function getCollectionClassName ()
	{
		return 'negociateurCollection';
	}
}
