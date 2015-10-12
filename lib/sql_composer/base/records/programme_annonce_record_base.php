<?php
class programme_annonceRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'programme_annonce';
	}
	public static function getCollectionClassName ()
	{
		return 'programme_annonceCollection';
	}
}
