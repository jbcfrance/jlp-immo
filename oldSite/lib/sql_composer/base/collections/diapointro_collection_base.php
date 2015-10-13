<?php
class diapointroCollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return 'diapointro';
	}
	public static function getRecordClassName ()
	{
		return 'diapointroRecord';
	}
}
