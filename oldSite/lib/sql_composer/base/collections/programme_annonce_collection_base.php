<?php
class programme_annonceCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'programme_annonce';
	}
	public static function getRecordClassName ()
	{
		return 'programme_annonceRecord';
	}
}
