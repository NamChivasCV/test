 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <header>
         <div class="header">
            <div class="container">

               <div class="header-logo"><a  class="logo" href="https://khophimz.com/" title="Phim Mới"><img width="368" class="lazy" src="<?php echo $logo ?>" data-src="<?php echo $logo ?>"></a></div>
               <div class="widget_search">
                  <form method="GET" id="form-search" action="tim-kiem/">
                     <div><input type="text" name="keyword" placeholder="Tìm: tên phim, đạo diễn, diễn viên" value=""><input id="searchsubmit" class="" value=" " type="submit"></div>
                  </form>
               </div>
               <div class="widget_user_header"><a class="button-register" ></a><a class="button-login" ></a></div>
		
			   
			   
			   
            </div>
         </div>
      </header>
      <div class="clear"></div>
      <div class="ad-container watch-banner-2" id="adtop-watch"></div>
      <nav>
         <div class="clear"></div>
         <div class="container">
            <div class="menu-menu-1-container">
               <ul id="mega-menu-1" class="menu row">
			   <li class="col-lg-6 an-mobile"><a title="Phimmoi" href="/"><i class="fa fa-home" aria-hidden="true"></i></a>
				   
				</li>
                  <li class="col-lg-6"><a title="Phim Mới" href="<?php echo $base ?>"> <i class="fa fa-angle-down" aria-hidden="true"></i> Phim Mới</a></li>
				  
				   <?php
								$nhomspcha=mysql_query("SELECT * FROM nhomsanphamcha where manhomcha<>55 order by sapxep asc");
								while($mangnhomcha = mysql_fetch_array($nhomspcha))
								{
									$tennhomcha = $mangnhomcha["tennhom"];
									$manhomchasd = $mangnhomcha["manhomcha"];
									$motavtn = $mangnhomcha["mota"];
									$linkkodausp = linkkhongdau($tennhomcha);
									$linkchinhnsp = $linkkodausp."-".$manhomchasd.".html";
							 ?>
                  <li class="col-lg-6">
				  <!---->
                     <a  href="<?php echo $linkchinhnsp;?>" title="<?php echo $tennhomcha ?>" > <i class="fa fa-angle-down" aria-hidden="true"></i> <?php echo $tennhomcha ?> 
					 
					  <?php
						  $sosp = mysql_query("SELECT count(maloai) as total FROM loaisanpham where manhomcha='$manhomchasd'");
							$row = mysql_fetch_assoc($sosp)['total'];
							
														 ?>
					</a>
							
					  <?php
						  $sosp = mysql_query("SELECT count(maloai) as total FROM loaisanpham where manhomcha='$manhomchasd'");
							$row = mysql_fetch_assoc($sosp)['total'];
							if($row >0){
														 ?>
                     <ul>
					  <?php
										$nhomspnho=mysql_query("SELECT * FROM loaisanpham where manhomcha = '$manhomchasd' ");
										while($mangnhomnho = mysql_fetch_array($nhomspnho))
										{
											$tennhomnho = $mangnhomnho["tenloai"];
											$manhoms = $mangnhomnho["maloai"];
											
											$chitiet1 = $mangnhomnho["chitiet"];
										
											$linkkodaunsp = linkkhongdau($tennhomnho);
											$linkchinhnnsp = $linkkodaunsp."-".$manhoms."/";
											if(trim($chitiet1)!='')
											{
									 ?>
												<li><a href="<?php echo $linkchinhnnsp ?>" title="<?php echo $tennhomnho ?>" ><i class="fa fa-angle-down" aria-hidden="true"></i> <?php echo $tennhomnho ?></a></li>
							<?php } 
							} ?>
                     </ul>
							<?php } ?>
                  </li>
								<?php }?>
								
								   <li><a href="toplist/" title="Phimmoi" ><i class="fa fa-angle-down" aria-hidden="true"></i> Toplist</a>
			  </ul>
            </div>
         </div>
      </nav>
      <div class="clear"></div>
	  

      
