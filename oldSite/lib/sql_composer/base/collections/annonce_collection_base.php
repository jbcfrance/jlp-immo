<?php
class annonceCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'annonce';
	}
	public static function getRecordClassName ()
	{
		return 'annonceRecord';
	}
}
