<?php
class passerelleRecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return 'passerelle';
	}
	public static function getCollectionClassName ()
	{
		return 'passerelleCollection';
	}
}
