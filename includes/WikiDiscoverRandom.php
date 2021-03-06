<?php
class WikiDiscoverRandom {
	public static function randomWiki( $state = 0, $category = 0, $language = 0 ) {
		$conditions = array();

		if ( $category ) {
			$conditions['wiki_category'] = $category;
		}

		if ( $language ) {
			$conditions['wiki_language'] = $language;
		}

		if ( $state === "inactive" ) {
			$conditions['wiki_inactive'] = 1;
		} elseif ( $state === "closed" ) {
			$conditions['wiki_closed'] = 1;
		} elseif ( $state === "open" ) {
			$conditions['wiki_closed'] = 0;
		}

		return self::randFromConds( $conditions );
	}

	protected static function randFromConds( $conds ) {
		global $wgCreateWikiDatabase;
		$dbr = wfGetDB( DB_REPLICA, [], $wgCreateWikiDatabase );

		$possiblewikis = $dbr->selectFieldValues( 'cw_wikis', 'wiki_dbname', $conds, __METHOD__ );

		$randwiki = $possiblewikis[array_rand($possiblewikis)];

		return $dbr->selectRow( 'cw_wikis', array( 'wiki_dbname', 'wiki_sitename', 'wiki_language', 'wiki_private', 'wiki_closed', 'wiki_inactive', 'wiki_category' ), array( 'wiki_dbname' => $randwiki ), __METHOD__ );
	}
}
