<select name="distance">
	<option value="0" <?php echo (($_GET["distance"]) == 0) ? "selected" : ""; ?> >Distance</option>
	<option value="3" <?php echo (($_GET["distance"]) == 3) ? "selected" : ""; ?> >< 3 KM</option>
	<option value="5" <?php echo (($_GET["distance"]) == 5) ? "selected" : ""; ?> >< 5 KM</option>
	<option value="10" <?php echo (($_GET["distance"]) == 10) ? "selected" : ""; ?> >< 10 KM</option>	
	<option value="15" <?php echo (($_GET["distance"]) == 15) ? "selected" : ""; ?> >< 15 KM</option>
	<option value="25" <?php echo (($_GET["distance"]) == 25) ? "selected" : ""; ?> >< 25 KM</option>
	<option value="50" <?php echo (($_GET["distance"]) == 50) ? "selected" : ""; ?> >< 50 KM</option>
	<option value="75" <?php echo (($_GET["distance"]) == 75) ? "selected" : ""; ?> >< 75 KM</option>		
</select>
