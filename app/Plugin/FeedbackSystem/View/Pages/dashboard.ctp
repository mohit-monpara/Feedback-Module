<?php 
echo 'Welcome  '.AuthComponent::user('fullname'); 
?>		

<?php if(!Auth::hasRoles(['developer'])) {?>
<div class="row">
	<div class="col-lg-3">
	<h3>Total Categories</h3>
	Total No. of Categories = <?php echo $categories; ?>
</div>

<div class="col-lg-3">
	<h3>Active Categories</h3>
<p>
Categories = <?php echo $activecategories; ?><br>
</div>

<div class="row">
	<div class="col-lg-3">
	<h3>Total Event</h3>
	Total No. of Events = <?php echo $events; ?>
</div>

<div class="col-lg-3">
	<h3>Active Events</h3>
<p>
Events = <?php echo $activeevents; ?><br>
</div>
<?php } ?>

<?php if(Auth::hasRoles(['developer','superadmin'])) {?>
<div class="row">
	<div class="col-lg-3">
	<h3>Total Categories</h3>
	Total No. of Categories = <?php echo $devcategories; ?>
</div>

<div class="col-lg-3">
	<h3>Active Categories</h3>
<p>
Categories = <?php echo $devactivecategories; ?><br>
</div>

<div class="row">
	<div class="col-lg-3">
	<h3>Total Event</h3>
	Total No. of Events = <?php echo $devevents; ?>
</div>

<div class="col-lg-3">
	<h3>Active Events</h3>
<p>
Events = <?php echo $devactiveevents; ?><br>
</div>

<?php } ?>