<table style="width: 100%;">
	<tr>
		<td valign="top">
			<fieldset>
				<legend><strong>Core</strong></legend>
				<table>
					<tr>
						<td>
							<label for="abbr">Abbreviation:</label>
						</td>
						<td>
							<input type="text" name="abbr" id="djb-rank-abbr" value="<?php echo $fields['abbr']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="order_id">Order:</label>
						</td>
						<td>
							<select name="order_id" id="order_id">
								<option value="">All</option>
							<?php
							foreach( $orders as $order ) {
							?>
								<option value="<?php echo $order->ID; ?>" <?php selected( $fields['order_id'], $order->ID ); ?>><?php echo $order->post_title; ?></option>
							<?php
							}//end foreach
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="sort_order">Sort Order:</label>
						</td>
						<td>
							<input type="text" name="sort_order" id="djb-rank-sort_order" value="<?php echo $fields['sort_order']; ?>"/>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset>
				<legend><strong>Sheet Stats</strong></legend>
				<table>
					<tr>
						<td>
							<label for="discipline_points">Discipline Points:</label>
						</td>
						<td>
							<input type="text" name="discipline_points" id="djb-rank-discipline_points" value="<?php echo $fields['discipline_points']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="force_points">Force Points:</label>
						</td>
						<td>
							<input type="text" name="force_points" id="djb-rank-force_points" value="<?php echo $fields['force_points']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="hand_to_hand_points">Hand to Hand Points:</label>
						</td>
						<td>
							<input type="text" name="hand_to_hand_points" id="djb-rank-hand_to_hand_points" value="<?php echo $fields['hand_to_hand_points']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="saber_points">Saber Points:</label>
						</td>
						<td>
							<input type="text" name="saber_points" id="djb-rank-saber_points" value="<?php echo $fields['saber_points']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="skill_points">Skill Points:</label>
						</td>
						<td>
							<input type="text" name="skill_points" id="djb-rank-skill_points" value="<?php echo $fields['skill_points']; ?>"/>
						</td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
