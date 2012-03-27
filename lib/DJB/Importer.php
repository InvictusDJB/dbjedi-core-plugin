<?php

namespace DJB;

class Importer {
	public function count_new() {
		if( ! $this->count_new ) {
			$loop = new \WP_Query("post_type={$this->post_type}&post_status=any");
			$this->count_new = $loop->found_posts;
		}//end if

		return $this->count_new;
	}//end count_new

	public function count_old() {
		if( ! $this->count_old ) {
			$this->count_old = $this->data( true );
		}//end if

		return $this->count_old;
	}//end count_old

	public function fully_imported() {
		return $this->count_new() >= $this->count_old();
	}//end fully_imported

	public static function get( $which ) {
		static $importers = array();

		if( ! $importers[ $which ] ) {
			$class = '\DJB\Importer\\' . $which;
			$importers[ $which ] = new $class;
		}//end if

		return $importers[ $which ];
	}//end get

	public function named_import( $post_type, $posts ) {
		global $wpdb;

		foreach( $posts as $post ) {
			$sql = "SELECT post_name FROM $wpdb->posts WHERE post_title = %s AND post_type = %s LIMIT 1";
			$exists = $wpdb->get_var( $wpdb->prepare( $sql, $post['post_title'], $post_type ) ); 

			$wpdb->flush();

			/*
			die( \DJB::dbug( $wpdb->prepare( $sql, $post['post_title'], $post_type ) ) );
			die( \DJB::dbug( $post['post_title'] ) );
			die( \DJB::dbug( $exists ) );
			//*/

			if( ! $exists ) {
				$custom_fields = $post['custom_fields'];
				unset( $post['custom_fields'] );
				$post_id = wp_insert_post( $post, $wp_error );

				if( $custom_fields ) {
					foreach( $custom_fields as $key => $value ) {
						if( 'parent_legacy_id' == $key ) {
							add_post_meta( $post_id, $key, $value, true );

							// find the parent
							$parent = new \WP_Query( "post_type={$post_type}&meta_key=legacy_id&meta_value={$value}" );
							$parent = $parent->get_posts();
							$parent = $parent[0];

							// set the post parent
							wp_update_post( array(
								'ID' => $post_id,
								'post_parent' => $parent->ID,
							));
						} else {
							add_post_meta( $post_id, $key, $value, true );
						}//end else
					}//edn foreach
				}//end if
			}//end if
		}//end foreach
	}//end named_import

	public function posts( $data, $status ) {
		static $valid_fields = array(
			'ID',
			'menu_order',
			'comment_status',
			'ping_status',
			'pinged',
			'post_author',
			'post_category',
			'post_content',
			'post_date',
			'post_date_gmt',
			'post_excerpt',
			'post_name',
			'post_parent',
			'post_password',
			'post_status',
			'post_title',
			'post_type',
			'tags_input',
			'to_ping',
			'tax_input',
		);

		$posts = array();
		foreach( $data as $d ) {
			$post = array(
				'post_type' => $this->post_type,
				'post_status' => $status,
				'custom_fields' => array(),
			);

			foreach( $d as $key => $value ) {
				if( in_array( $key, $valid_fields ) ) {
					$post[ $key ] = $value;
				} else {
					$post[ 'custom_fields' ][ $key ] = $value;
				}
			}//end foreach

			$posts[] = $post;
		}//end foreach

		return $posts;
	}//end posts

	public function page() {
		if( $_GET['import'] ) {
			$method = $_POST['how'] ?: 'draft';
			$this->named_import( $this->post_type, $this->posts( $this->data(), $method ) );
		}//end if

		if( $_GET['purge'] ) {
			$this->purge();
		}//end if
?>
<div class="wrap">
	<h2><?php echo $this->page_title; ?> Importer</h2>
	<form method="post" action="admin.php?page=djb-data-importer-<?php echo $this->post_type; ?>&import=true">
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php echo $this->page_title; ?> in Old DJB Site</th>
			<td><?php echo number_format( $this->count_old() ); ?></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php echo $this->page_title; ?> in New Site</th>
			<td>
				<?php echo number_format( $this->count_new() ); ?>
				&mdash; <a id="purge" style="color: red; border-color: red;" href="admin.php?page=djb-data-importer-<?php echo $this->post_type; ?>&purge=true">Purge Data</a>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Import As:</th>
			<td>
				<select name="how">
					<option value="draft">Draft</option>
					<option value="publish">Published</option>
				</select>
			</td>
		</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Import') ?>" />
		</p>
	</form>
	<script>
		jQuery(function() {
			jQuery('#purge').click(function( e ) {
				return confirm('Are you sure you want to purge all that data?');
			});
		});
	</script>

</div>
<?php
	}//end page

	public function purge() {
		global $wpdb;

		if( ! current_user_can( 'edit_user' ) ) {
			die('You do not have access to purge ' . $this->post_type );
		}//end if

		$posts = new \WP_Query("post_type={$this->post_type}&posts_per_page=-1&post_status=any");
		$posts = $posts->get_posts();
		foreach( $posts as $post ) {
			wp_delete_post( $post->ID, TRUE );
		}//end foreach

		\DJB::redirect('admin.php?page=djb-data-importer-' . $this->post_type);
	}//end purge_users
}//end class DJB\Importer
