<?php

namespace DJB\Importer;

class Users extends \DJB\Importer {
	public $page_title = 'Users';

	public function data( $from = 0, $num = 1 ) {
		global $wpdb;

		$base_fields = array(
			'user_login',
		  'user_nicename',
			'user_email',
			'user_registered',
			'user_status',
			'display_name',
		);

		$num = (int) $num;

		//\DJB::db('olddjb')->debug = true;
		//\DJB::db('djb')->debug = true;

		$sql = "
			SELECT TOP {$num} member_id pin,
			       'm' + CONVERT(varchar(5), member_id) user_login,
			       'm' + CONVERT(varchar(5), member_id) user_nicename,
			       name display_name,
				     email user_email,
						 join_date user_registered ,
						 0 user_status,
						 pword,
						 master_id master_pin,
						 ordr order_id,
						 surname,
						 deleted,
						 active,
						 invisible
			  FROM members
			 WHERE member_id > ?
			 ORDER BY member_id
		";

		if( $data = \DJB::db('olddjb')->GetAll( $sql, array( $from ) ) ) {

			$meta_fields = array(
				'order_id',
				'surname',
				'master_pin',
				'deleted',
				'active',
				'invisible',
				'recruiter_id',
				'quote',
				'gmrg_id',
				'url',
				'saber',
				'warbanner',
				'robes',
				'loa',
				'loa_start',
				'loa_end',
				'loa_reason',
				'avatar',
				'probation',
				'probation_start',
				'probation_end',
				'country',
				'timezone',
				'personal_image',
				'xfire',
				'xbox',
				'playstation',
				'wii',
				'saber_locked',
				'warbanner_locked',
				'robes_locked',
				'gender',
				'gmrg_rank_id',
				'prestige',
				'credits',
				'credits_earned',
				'swtor',
				'tor_characters',
				'tor_rank',
			);

			$default_meta = array(
				'rich_editing' => 'true',
				'comment_shortcuts' => 'false',
				'admin_color' => 'fresh',
				$wpdb->prefix . 'capabilities' => 'a:1:{s:10:"subscriber";b:1;}',
				'default_password_nag' => '0',
			);

			foreach( $data as &$user ) {
				$user['user_email'] = strtolower( $user['user_email'] );
				$user['meta'] = array();
				$user['meta']['pin'] = $user['pin'];
				$user['meta']['nickname'] = $user['display_name'];

				if( $user['master_pin'] ) {
					$user['meta']['surname'] = $user['surname'];
				}//end if

				if( $user['master_pin'] ) {
					$user['meta']['master_pin'] = $user['master_pin'];
				}//end if

				$user['meta']['deleted'] = $user['deleted'];
				$user['meta']['active'] = $user['active'];
				$user['meta']['invisible'] = $user['invisible'];

				$order_slug = str_replace(' ', '-', strtolower( trim( $user['order_id'] ) ) );
				$order = \DJB\Core\Order::get( $order_slug );
				$user['meta']['order_id'] = $order->ID;

				$user['meta'] = array_merge( $default_meta, $user['meta'] );

				unset(
					  $user['surname']
					, $user['deleted']
					, $user['active']
					, $user['invisible']
					, $user['master_pin']
					, $user['ordr']
					, $user['order_id']
				);

				$sql = "
					SELECT m.slug,
								 mm.meta_value
						FROM db_member_meta mm
								 JOIN db_meta m
									 ON m.id = mm.meta_key_id 
					 WHERE mm.member_id = ?
				";

				$meta = \DJB::db('djb')->GetAll( $sql, array( $user['pin'] ) );

				foreach( $meta as $m ) {
					if( in_array( $m['slug'], $meta_fields ) ) {
						$user['meta'][ $m['slug'] ] = $m['meta_value'];
					}//end if
				}//end foreach

				unset($user['pin']);
			}//end foreach
		}//end if
		return $data;
	}//end data

	public function user_import( $users ) {
		global $wpdb;

		echo '<div style="float:right;">';
		foreach( (array) $users as $user ) {
			$meta = $user['meta'];
			$password = $user['pword'];
			unset( $user['meta'], $user['pword'] );

			$insert = false;

			// if the pin number doesn't exist...update it
			if( ! ( $user_id = username_exists( $user['user_login'] ) ) ) {
				$insert = true;
				echo "Inserting: {$user['display_name']} ({$user['user_login']})<br/>";
				$wpdb->insert( $wpdb->users, $user );
				$user_id = $wpdb->insert_id;
			}//end if

			if( $user_id ) {
				if( ! $insert ) {
					echo "Updating: {$user['display_name']} ({$user['user_login']})<br/>";
				}//end if

				// let's always set the password to make sure they are in sync with the djb site
				wp_set_password( $password, $user_id );

				// update all the meta
				foreach( $meta as $key => $value ) {
					update_user_meta( $user_id, $key, $value );
				}//end foreach
			}//end if
		}//end foreach
		echo '</div>';

		return $user_id;
	}//end user_import

	public function page() {
?>
<div class="wrap">
	<h2><?php echo $this->page_title; ?> Importer</h2>
	<form method="post" action="admin.php?page=djb-data-importer-djb-users&import=true">
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php echo $this->page_title; ?> in Old DJB Site</th>
			<td>
			<?php 
				$sql = "SELECT count(*) FROM members";
				echo \DJB::db('olddjb')->GetOne( $sql );
			?>
			</td>
			<td rowspan="6">
<?php
		if( $_GET['import'] ) {
			$last_import = $this->user_import( $this->data( (int) $_POST['start'], (int) $_POST['num'] ) );
		}//end if
?>
			</div>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo $this->page_title; ?> in New Site</th>
			<td>
			<?php 
				$users = count_users(); 
				echo $users['total_users'];
			?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Last User Imported</th>
			<td>
			<?php
				if( $last_import ) {
					$user = get_userdata( $last_import );
				} else {
					$args = array(
						'orderby' => 'ID',
						'order' => 'DESC',
						'number' => 1,
						'fields' => 'all',
					);
					$user_search = new \WP_User_Query( $args );
					$user = $user_search->get_results();
					$user = $user[0];
				}//end else
				echo "{$user->display_name} (PIN: {$user->user_login})";
			?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Pin to start:</th>
			<td><input name="start" value="<?php echo substr( $user->user_login, 1 ); ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row">Num to import:</th>
			<td><input name="num" value="<?php echo $_POST['num'] ?: 1; ?>"/></td>
		</tr>
		<tr valign="top">
			<th scope="row"></th>
			<td>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Import') ?>" />
				</p>
			</td>
		</tr>
		</table>
	</form>
</div>
<?php
	}//end page
}//end Users
