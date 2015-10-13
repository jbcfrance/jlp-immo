<?php
class annonceRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'annonce';
	}
	public static function getCollectionClassName ()
	{
		return 'annonceCollection';
	}
}
