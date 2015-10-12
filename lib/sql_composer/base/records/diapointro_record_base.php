<?php
class diapointroRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'diapointro';
	}
	public static function getCollectionClassName ()
	{
		return 'diapointroCollection';
	}
}
