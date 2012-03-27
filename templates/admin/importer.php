<?php

$importers = \DJB\Admin::importers();
$dependencies = \DJB\Admin::importer_dependencies();

function dump_dependencies( $importers, $dependencies, $parent = null, $children = array() ) {

	if( $parent ) {
		$name = $importers[ $parent ];

		$new = number_format( \DJB\Importer::get( $name )->count_new() );
		$old = number_format( \DJB\Importer::get( $name )->count_old() );
		$fully_imported = \DJB\Importer::get( $name )->fully_imported();
		$children_imported = true;

		echo '<li class="'. ( ! $fully_imported ? 'pending' : '' ) .'">';
		if( $fully_imported ) {
			echo "<a href='admin.php?page=djb-data-importer-{$parent}'>{$name}</a> ";
		} else {
			foreach( (array) $dependencies[ $parent ] as $child => $child_children ) {
				if( ! \DJB\Importer::get( $importers[ $child ] )->fully_imported() ) {
					$children_imported = false;
					break;
				}//end if
			}//end foreach

			if( ! $children_imported ) {
				echo "{$name} ";
			} else {
				echo "<a href='admin.php?page=djb-data-importer-{$parent}'>{$name}</a> ";
			}//end else
		}//end else

		echo ' &mdash; ';

		if( $children_imported ) {
			echo "({$new} out of {$old})";
		} else {
			echo "<small>(You must import this data element's children)</small>";
		}//end else
	}//end if

	if( $children ) {
		echo '<ul style="list-style:disc;padding-left:2em;margin: 0.5em 0;">';
		foreach( $children as $p => $c ) {
			dump_dependencies( $importers, $dependencies, $p, $c );
		}///end foreach
		echo '</ul>';
	}//end if

	if( $parent ) {
		echo '</li>';
	}//end if
}//end dump_dependencies
?>
<div class="wrap">
	<h2>Importer Dashboard</h2>
	
	<h3>Status &amp; Dependencies</h3>
	<ul>
<?php
dump_dependencies( $importers, $dependencies, null, $dependencies );
?>
	</ul>
</div>
