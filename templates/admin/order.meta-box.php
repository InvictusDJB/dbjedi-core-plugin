<table>
	<tr>
		<td>
			<label for="path">Path:</label>
		</td>
		<td>
			<select name="path" id="djb-order-path">
				<option value="dark" <?php selected( $fields['path'], 'dark' ); ?>>Dark</option>
				<option value="light" <?php selected( $fields['path'], 'light' ); ?>>Light</option>
			</select>
		</td>
	</tr>
</table>
