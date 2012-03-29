<?php

namespace DJB;

class Links {
	public static function dossier( $id ) {
		if( $user = get_userdata( $id ) ) {
			return "<a href='user-edit.php?user_id={$user->ID}'>{$user->display_name}</a>";
		}//end if

		return '';
	}//end dossier
}//end DJB\Links
