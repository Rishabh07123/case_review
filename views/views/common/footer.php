<?php $year = date ( "Y" ); ?>
<footer>          
	<div class="container">
		<div class="col-sm-4 col-sm-offset-4">
		<div class="text-center wow fadeInUp animated footer_p" style="color: #5BA1FF;font-size: 14px; visibility: visible; animation-name: fadeInUp;"><?php echo "Copyright © $year CaseReview Developed By Nablasol <br>All Rights Reserved. "?> </div>
		</div>	
	</div>	
</footer>
	<!-- WIDGETS -->

<script type='text/javascript' src="<?php echo base_url("assets/tether/js/tether.js");?>"></script>
<script type='text/javascript' src="<?php echo base_url("assets/bootstrap/js/bootstrap.js");?>"></script>

<!-- Bootstrap Dropdown -->


<!-- Bootstrap Progress Bar -->
<script type='text/javascript' src="<?php echo base_url("assets/widgets/progressbar/progressbar.js");?>"></script>

<!-- Bootstrap Buttons -->

<!-- Superclick -->

<script type='text/javascript' src="<?php echo base_url("assets/widgets/superclick/superclick.js");?>"></script>

<!-- Input switch alternate -->

<script type='text/javascript' src="<?php echo base_url("assets/widgets/input-switch/inputswitch-alt.js");?>"></script>

<!-- Slim scroll -->

<script type='text/javascript' src="<?php echo base_url("assets/widgets/slimscroll/slimscroll.js");?>"></script>
<!-- Slidebars -->

<script type='text/javascript' src="<?php echo base_url("assets/widgets/slidebars/slidebars.js");?>"></script>
<script type='text/javascript' src="<?php echo base_url("assets/widgets/slidebars/slidebars-demo.js");?>"></script>
<!-- PieGage -->


<script type='text/javascript' src="<?php echo base_url("assets/widgets/charts/piegage/piegage.js");?>"></script>
<script type='text/javascript' src="<?php echo base_url("assets/widgets/charts/piegage/piegage-demo.js");?>"></script>

<!-- Screenfull -->

<script type='text/javascript' src="<?php echo base_url("assets/widgets/screenfull/screenfull.js");?>"></script>

<!-- Content box -->

<script type='text/javascript' src="<?php echo base_url("assets/widgets/content-box/contentbox.js");?>"></script>

<!-- Overlay -->

<script type='text/javascript' src="<?php echo base_url("assets/widgets/overlay/overlay.js");?>"></script>

<!-- Widgets init for demo -->
<script type='text/javascript' src="<?php echo base_url("assets/js-init/widgets-init.js");?>"></script>

<!-- Theme layout -->

<script type='text/javascript' src="<?php echo base_url("assets/themes/admin/layout.js");?>"></script>

<!-- Theme switcher -->

<script type='text/javascript' src="<?php echo base_url("assets/widgets/theme-switcher/themeswitcher.js");?>"></script>
<script>
/* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
} 
</script>

<script type='text/javascript' src="<?php echo base_url("assets/js-core/jquery.tablenavigator.js");?>"></script>

<script>
    $("#tab_pps_table").tableNavigator({
        input: true
    })

    $("#pps_table").tableNavigator({
        input: true
    })
</script>

</div><!-- page-content ends -->
</div><!-- page-content-wrapper ends -->
</div><!-- page-header ends -->
</div><!-- page-wrapper ends -->
</body>
</html>