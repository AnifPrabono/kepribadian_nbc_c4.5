<?php
session_start();
?>


<div class="sticky-header header-section " style="z-index:599">
	<div class="header-left" style="margin-bottom: 7px;">
				
				<!--toggle button start-->
				<button id="showLeftPush"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
				
				<!--notification menu end -->
			<div class="clearfix"> </div>
			</div>
			<div class="header-right">				
				
				
			<div class="profile_details">		
					<ul>
						<?php if( !empty($_SESSION['kepribadian_nbc_c4.5_nama'])) { ?>
						<li class="dropdown profile_details_drop" style="z-index:599" >
							<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">
								<?php
									if ($_SESSION['kepribadian_nbc_c4.5_level'] == 1){
										?>

									<span class="prfil-img"><img width=50 src="images/admin1.png" alt=""> </span> 
									<?php
										}else{
											?>
								
									<span class="prfil-img"><img width=39 src="images/user.png" alt=""> </span> 	
									<?php
										}
									?>
									<div class="user-name">
										<p style="margin-top:10px">Hi <?php echo $_SESSION['kepribadian_nbc_c4.5_nama'] ?> </p>
									</div>
									
									
								</div>	
							</a>
						</li>
						<?php
						}else{
						?>
						<li class="position-relative" style="top:10px">
							<a href="login.php">
							<button   class="btn btn-dark"> Login </button>
							</a>
						</li>
						<?php }?>
					</ul>
				</div>
				<div class="clearfix"> </div>				
			</div>
	<div class="clearfix"> </div>	
</div>