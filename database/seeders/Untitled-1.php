<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pay Slip</title>
<style type="text/css">
	@media screen {
		body{
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}
	}
@media print {
  .page-break {
    clear: both;
    page-break-after: always; 
	margin: 0;
	padding: 0;
  }
}

</style>
</head>
<body style="line-height:12px;">

<table align="left" border="0" cellpadding="0" cellspacing="0">
<?php
$count = 0;
foreach($values as $rows)
{
	$count++;
	echo "<tr>";
?>
	<td height="450" width="450" valign="top" align="center">  
  	<div style="width:100%; height:auto; ">
	<div style="width:305px; height:auto; overflow:hidden; font-size:8px; font-family: SolaimanLipi;">
		<div style="width:280px; height:auto; margin:0 auto; text-align:center;">

			<?php $this->load->view('head_bangla'); ?>
			<span style="font-size:14px; font-weight:bold;">&#2474;&#2503; - &#2488;&#2509;&#2482;&#2495;&#2474;-<span style="font-family:'Times New Roman', Times, serif;">
				<?php
						$first= $rows["salary_month"];
						$first_y=trim(substr($first,0,4));
						$first_m=trim(substr($first,5,2));
						$first_d=trim(substr($first,8,2));
						$month_format = date("F", mktime(0, 0, 0, $first_m, 1, $first_y));
						echo "$month_format, $first_y";
				?>
			</span></span>
		</div>
	<div style="width:300px; height:auto; overflow:hidden; border:1px solid #000000;">
		<div style="width:100%; border-bottom:1px solid #000000; height:auto; overflow:hidden;">
			<table width="300" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="76">&#2472;&#2494;&#2478; </td>
					<td width="96"><font style="font-family:'Times New Roman', Times, serif;">: <strong><?php echo $rows["emp_full_name"];   ?></strong> </font></td>
					<td width="72"></td>
					<td width="75"></td>
				</tr>
				
				<tr>
					<td width="76">&#2474;&#2470;&#2476;&#2496;</td>
					<td width="96"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["desig_name"];   ?> </font></td>
					<td width="72">ব্যাংক অ্যাকাউন্ট</td>
					<td width="75"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["mobile"];   ?> </font></td>
				</tr>
				<tr>
					<td width="76">&#2453;&#2494;&#2480;&#2509;&#2465;</td>
					<td width="96"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["emp_id"];   ?></font></td>
					<td width="72">&#2488;&#2503;&#2453;&#2486;&#2472; </td>
					<td width="75"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["sec_name"];   ?></font></td>
				</tr>
				<tr>
					<td width="76">&#2479;&#2507;&#2455;&#2470;&#2494;&#2472;&#2503;&#2480; &#2468;&#2494;&#2480;&#2495;&#2454; </td>
					<td width="96"><font style="font-family:'Times New Roman', Times, serif;">: 
					<?php 
					$date =  $rows["emp_join_date"];  
					$year=trim(substr($date,0,4));
					$month=trim(substr($date,5,2));
					$day=trim(substr($date,8,2));
					$date_format = date("d-M-y", mktime(0, 0, 0, $month, $day, $year));
					echo $date_format; 
					?>
					</font></td>
					<td width="72">&#2482;&#2494;&#2439;&#2472; </td>
					<td width="75"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["line_name"];   ?> </font></td>
				</tr>
				<tr>
					<td width="76">&#2478;&#2507;&#2463; &#2470;&#2495;&#2472; </td>
					<td width="96"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["total_days"];   ?> </font></td>
					<td width="72">&#2465;&#2495;&#2474;&#2494;&#2480;&#2509;&#2463;&#2478;&#2503;&#2472;&#2509;&#2463; </td>
					<td width="75"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["dept_name"];   ?> </font></td>
				</tr>
				<tr>
					<td width="76">&#2478;&#2507;&#2463; &#2453;&#2480;&#2509;&#2478; &#2470;&#2495;&#2476;&#2488; </td>
					<td width="96"><font style="font-family: SutonnyMJ;">: 
					<?php 
					$holidy_or_weeked = $rows["holiday_or_weeked"];   
					echo $rows["total_days"]- $holidy_or_weeked;//$rows["num_of_workday"];
					?> 
					</font></td>
					<td width="72">&#2459;&#2497;&#2463;&#2495; </td>
					<td width="75">
					<font style="font-family: SutonnyMJ;">: 
					<?php 
					$c_l = $rows["c_l"];  
					$s_l = $rows["s_l"];  
					$e_l = $rows["e_l"];   
					echo $total_leave = $c_l + $s_l + $e_l;
					?> 
					</font>
					</td>
				</tr>
				<tr>
					<td width="76">&#2478;&#2507;&#2463; &#2437;&#2472;&#2497;&#2474;&#2488;&#2509;&#2469;&#2495;&#2468;&#2495; </td>
					<td width="96"> <font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["absent_days"];   ?></font> ,&nbsp;&nbsp; &nbsp; <span style="font-size: 8px !important">LO : </span> <span  style="font-family: SutonnyMJ; font-size: 11px"><?php echo
					$rows["l_l"]; ?></span> </td>
					<td width="72">&#2441;&#2474;&#2488;&#2509;&#2469;&#2495;&#2468;&#2495; </td>
					<td width="75"><font style="font-family: SutonnyMJ; ">: <?php echo $total_days = $rows["att_days"];   ?> </font>  </td>
				</tr>
				
				<tr>
					<td width="76">&#2488;&#2494;&#2474;&#2509;&#2468;&#2494;&#2489;&#2495;&#2453; &#2459;&#2497;&#2463;&#2495; </td>
					<td width="96">   <font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["weeked"];   ?> </font>  </td>
					<td width="72">&#2451;&#2463;&#2495; &#2456;&#2472;&#2509;&#2463;&#2494; </td>
					<td width="75"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["ot_hour"];   ?> </font> </td>
				</tr>
				<tr>
					<td width="76" height="14">&#2437;&#2472;&#2509;&#2479;&#2494;&#2472;&#2509;&#2479; &#2459;&#2497;&#2463;&#2495; 
					</td>
					<td width="96"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["holidy"];   ?> </font> </td>
					<td width="72">&#2451;&#2463;&#2495; &#2480;&#2503;&#2463; </td>
					<td width="75"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["ot_rate"];   ?> </font></td>
				</tr>
				
			</table> 
		</div>
	<div style="width:100%; border-bottom:1px solid #000000; height:auto; overflow:hidden;">
		
		<div style="float:left; width:55px; height: auto; position:relative; left:5px; top:18px; font-weight:bold;">  <!--position: relative; top: 18px; left: 5px;-->
		
		(&#2453;) &#2476;&#2503;&#2468;&#2472;
		</div>
		<div style="float: left; width:240px; border-left:1px solid #000000;" > 
		<table width="239" cellspacing="0" cellpadding="0">
		<tr>
			<td width="160">&#2478;&#2498;&#2482; &#2476;&#2503;&#2468;&#2472; </td>
			<td>:</td>
			<td width="70" align="center"><font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["basic_sal"];   ?> </font></td>
		</tr>
		<tr>
			<td width="160">&#2476;&#2494;&#2524;&#2496; &#2477;&#2494;&#2524;&#2494; </td>
			<td>:</td>
			<td width="12" align="center">  <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["house_r"];   ?> </font></td>
		</tr>
		
		<tr>
			<td width="160">&#2458;&#2495;&#2453;&#2495;&#2510;&#2488;&#2494; &#2477;&#2494;&#2468;&#2494;	  	  </td>
			<td>:</td>
			<td width="12" align="center"><font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["medical_a"];   ?> </font> </td>
		</tr>
		
		<tr>
			<td width="160"> যাতায়াত ভাতা</td>
			<td>:</td>
			<td width="12" align="center"><font style="font-family: SutonnyMJ;"><?php echo $trans_allow = $rows["trans_allow"];   ?> </font> </td>
		</tr>
		
		<tr>
			<td width="160">খাদ্য ভাতা</td>
			<td>:</td>
			<td width="12" align="center"><font style="font-family: SutonnyMJ;"><?php echo $food_allow = $rows["food_allow"];   ?> </font> </td>
		</tr>
		
		<tr>
			<td width="160" height="14">&#2478;&#2507;&#2463;</td>
			<td>:</td>
			<td width="12" align="center">  <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["gross_sal"];   ?> </font></td>
		</tr>
	</table>
		
		</div>
		</div>
	
	<div style="border-bottom:1px solid #000000;"><table width="295" cellspacing="0" cellpadding="0">
		<tr>
		<td width="239"><span style="position:relative; left:5px; font-weight:bold;"> (&#2454;)&#2437;&#2472;&#2497;&#2474;&#2488;&#2509;&#2469;&#2495;&#2468;&#2495; &#2453;&#2480;&#2509;&#2468;&#2472; </span>
		<td width="38"><font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["abs_deduction"];   ?> </font></td>
		</tr></table></div>
			
	<div style="border-bottom:1px solid #000000;"><table width="295" cellspacing="0" cellpadding="0">
		<tr>
		<td width="239"><span style="position:relative; left:5px; font-weight:bold;"> (&#2455;) &#2474;&#2509;&#2480;&#2470;&#2503;&#2527; &#2476;&#2503;&#2468;&#2472; </span>
		<td width="38">  <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["pay_wages"];   ?> </font>                   </td>
		</tr></table></div>
	<div style="width:100%; border-bottom:1px solid #000000; height:auto; overflow:hidden;">
		
		<div style="float:left; width:55px; height: auto; position:relative; left:5px; top:30px; font-weight:bold;"> (&#2456;) &#2477;&#2494;&#2468;&#2494; </div>
			<div style="float: left; width:240px; border-left:1px solid #000000;" > 
				<table width="240">
			
					<tr>
						<td width="154">&#2489;&#2494;&#2460;&#2495;&#2480;&#2494; &#2476;&#2507;&#2472;&#2494;&#2488; </td>
						<td>:</td>
						<td width="70" align="center"> <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["att_bonus"];   ?></font> </td>
					</tr>
					

					
					<tr>
						<td width="154">&#2437;&#2472;&#2509;&#2479;&#2494;&#2472;&#2509;&#2479;</td>
						<td>:</td>
						<td width="70" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["others_allaw"];   ?> </font>  </td>
					</tr>
					
					
					<tr>
						<td width="154">&#2478;&#2507;&#2463; &#2477;&#2494;&#2468;&#2494; </td>
						<td>:</td>
						<td width="70" align="center"> <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["total_allaw"];   ?> </td>
					</tr>
					
				</table>
			
			</div>
		</div>
				
		<div style="border-bottom:1px solid #000000;"><table width="298" cellspacing="0" cellpadding="0">
		<tr>
		<td width="239"><span style="position:relative; left:5px; font-weight:bold;"> (&#2457;) &#2451;&#2477;&#2494;&#2480; &#2463;&#2494;&#2439;&#2478;</span>
		</td><td width="38"> 
		<span style="font-family: SutonnyMJ; " > <?php echo $total_days = $rows["ot_amount"];   ?> </span>
		</td>
		</tr></table></div>
		<div style="border-bottom:1px solid #000000;"><table width="298">
		<tr>
		<td width="243"><span style="position:relative; left:2px; font-weight:bold;"> (&#2458;) &#2478;&#2507;&#2463; &#2476;&#2503;&#2468;&#2472;</span> &nbsp;(&#2455; + &#2456; + &#2457; ) 
		<td width="43"><font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["gross_pay"];   ?> </font></td>
		</tr></table></div>
		
		<div style="width:100%; border-bottom:1px solid #000000; height:auto; overflow:hidden;">
		
		<div style="float:left; width:53px; position:relative; left:5px; top:25px; font-weight:bold;">(&#2459;) &#2453;&#2480;&#2509;&#2468;&#2472; </div>
		<div style="float: left; width:240px; border-left:1px solid #000000;" > 
		<table width="237">
		
		<tr>
			<td width="152"> &#2437;&#2455;&#2509;&#2480;&#2496;&#2478; </td>
			<td >:</td>
			<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["adv_deduct"];   ?> </font></td>
		</tr>
		
		<tr>
			<td width="152">&#2437;&#2472;&#2509;&#2479;&#2494;&#2472;&#2509;&#2479;</td>
			<td >:</td>
			<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["others_deduct"];   ?> </font></td>
		</tr>
		<tr>
			<td width="152">স্ট্যাম্প</td>
			<td >:</td>
			<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $stamp_value = 0;   ?> </font></td>
		</tr>
		
		<tr>
			<td width="152">&#2474;&#2509;&#2480;&#2477;&#2495;&#2465;&#2503;&#2472;&#2509;&#2463; &#2475;&#2494;&#2472;&#2509;&#2465; </td>
			<td >:</td>
			<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["provident_fund"];   ?> </font></td>
		</tr>
		
		<tr>
			<td width="152">&#2478;&#2507;&#2463; &#2453;&#2480;&#2509;&#2468;&#2472; </td>
			<td >:</td>
			<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["total_deduct"]+$stamp_value;   ?> </font></td>
		</tr>
		
	</table>
		
		</div>
		</div>
		
		<div><table width="295" height="17" cellspacing="0" cellpadding="0">
		<tr>
		<td width="215">&#2488;&#2480;&#2509;&#2476;&#2478;&#2507;&#2463; &#2474;&#2509;&#2480;&#2470;&#2503;&#2527;  (&#2458;- &#2459; ) </td>
		<td >:</td>
		<td width="78" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["net_pay"]-$stamp_value;   ?> </font></td>
		</tr></table></div>
	</div>
	<div style="text-align:left; font-size:14px; padding-top:5px;">
		Authority Signature : <span><img src="<?php echo base_url();?>images/signature.png" width="100" height="auto" /></span>
	</div>
	</div>
</div>
	</td>
    <td height="500" width="450" valign="top" align="center">  
  	<div style="width:100%; height:auto; ">
<div style="width:305px; height:auto; overflow:hidden; font-size:8px; font-family: SolaimanLipi;margin-bottom:10px;">
 <div style="width:280px; height:auto; margin:0 auto; text-align:center;">
 <?php $this->load->view('head_bangla'); ?>
   	 
   <span style="font-size:14px; font-weight:bold;">&#2474;&#2503; - &#2488;&#2509;&#2482;&#2495;&#2474;-<span style="font-family:'Times New Roman', Times, serif;">
   <?php
	$first= $rows["salary_month"];
	$first_y=trim(substr($first,0,4));
	$first_m=trim(substr($first,5,2));
	$first_d=trim(substr($first,8,2));
	$month_format = date("F", mktime(0, 0, 0, $first_m, 1, $first_y));
	echo "$month_format, $first_y";
   ?>
   </span></span>
 </div>
  <div style="width:300px; height:auto; overflow:hidden; border:1px solid #000000;">
  
  
	<div style="width:100%; border-bottom:1px solid #000000; height:auto; overflow:hidden;">
	<table width="300" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="76">&#2472;&#2494;&#2478; </td>
			<td width="96"><font style="font-family:'Times New Roman', Times, serif;">: <strong><?php echo $rows["emp_full_name"];   ?></strong> </font></td>
			<td width="72"></td>
			<td width="75"></td>
		</tr>
		
		<tr>
			<td width="76">&#2474;&#2470;&#2476;&#2496;</td>
			<td width="96"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["desig_name"];   ?> </font></td>
			<td width="72">ব্যাংক অ্যাকাউন্ট</td>
			<td width="75"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["mobile"];   ?> </font></td>
		</tr>
		<tr>
			<td width="76">&#2453;&#2494;&#2480;&#2509;&#2465;</td>
			<td width="96"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["emp_id"];   ?></font></td>
			<td width="72">&#2488;&#2503;&#2453;&#2486;&#2472; </td>
			<td width="75"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["sec_name"];   ?></font></td>
		</tr>
		<tr>
			<td width="76">&#2479;&#2507;&#2455;&#2470;&#2494;&#2472;&#2503;&#2480; &#2468;&#2494;&#2480;&#2495;&#2454; </td>
			<td width="96"><font style="font-family:'Times New Roman', Times, serif;">: 
			<?php 
			$date =  $rows["emp_join_date"];  
			$year=trim(substr($date,0,4));
			$month=trim(substr($date,5,2));
			$day=trim(substr($date,8,2));
			$date_format = date("d-M-y", mktime(0, 0, 0, $month, $day, $year));
			echo $date_format; 
			?>
			</font></td>
			<td width="72">&#2482;&#2494;&#2439;&#2472; </td>
			<td width="75"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["line_name"];   ?> </font></td>
		</tr>
		<tr>
			<td width="76">&#2478;&#2507;&#2463; &#2470;&#2495;&#2472; </td>
			<td width="96"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["total_days"];   ?> </font></td>
			<td width="72">&#2465;&#2495;&#2474;&#2494;&#2480;&#2509;&#2463;&#2478;&#2503;&#2472;&#2509;&#2463; </td>
			<td width="75"><font style="font-family:'Times New Roman', Times, serif;">: <?php echo $rows["dept_name"];   ?> </font></td>
		</tr>
		<tr>
			<td width="76">&#2478;&#2507;&#2463; &#2453;&#2480;&#2509;&#2478; &#2470;&#2495;&#2476;&#2488; </td>
			<td width="96"><font style="font-family: SutonnyMJ;">: 
			<?php 
			$holidy_or_weeked = $rows["holiday_or_weeked"];   
			echo $rows["total_days"]- $holidy_or_weeked ;//$rows["num_of_workday"];
			?> 
			</font></td>
			<td width="72">&#2459;&#2497;&#2463;&#2495; </td>
			<td width="75">
			<font style="font-family: SutonnyMJ;">: 
			<?php 
			$c_l = $rows["c_l"];  
			$s_l = $rows["s_l"];  
			$e_l = $rows["e_l"];   
			echo $total_leave = $c_l + $s_l + $e_l;
			?> 
			</font>
			</td>
		</tr>
		<tr>
			<td width="76">&#2478;&#2507;&#2463; &#2437;&#2472;&#2497;&#2474;&#2488;&#2509;&#2469;&#2495;&#2468;&#2495; </td>
			<td width="96"> <font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["absent_days"];   ?></font> ,&nbsp;&nbsp; &nbsp; <span style="font-size: 8px !important">LO : </span> <span  style="font-family: SutonnyMJ; font-size: 11px"><?php echo
			$rows["l_l"]; ?></span> </td>
			<td width="72">&#2441;&#2474;&#2488;&#2509;&#2469;&#2495;&#2468;&#2495; </td>
			<td width="75"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["att_days"];   ?> </font>  </td>
		</tr>
		
		<tr>
			<td width="76">&#2488;&#2494;&#2474;&#2509;&#2468;&#2494;&#2489;&#2495;&#2453; &#2459;&#2497;&#2463;&#2495; </td>
			<td width="96">   <font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["weeked"];   ?> </font>  </td>
			<td width="72">&#2451;&#2463;&#2495; &#2456;&#2472;&#2509;&#2463;&#2494; </td>
			<td width="75"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["ot_hour"];   ?> </font> </td>
		</tr>
		<tr>
			<td width="76" height="14">&#2437;&#2472;&#2509;&#2479;&#2494;&#2472;&#2509;&#2479; &#2459;&#2497;&#2463;&#2495; 
		    </td>
			<td width="96"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["holidy"];   ?> </font> </td>
			<td width="72">&#2451;&#2463;&#2495; &#2480;&#2503;&#2463; </td>
			<td width="75"><font style="font-family: SutonnyMJ;">: <?php echo $total_days = $rows["ot_rate"];   ?> </font></td>
		</tr>
		
	</table> 
	</div>
	<div style="width:100%; border-bottom:1px solid #000000; height:auto; overflow:hidden;">
	
	<div style="float:left; width:55px; height: auto; position:relative; left:5px; top:18px; font-weight:bold;">  <!--position: relative; top: 18px; left: 5px;-->
	
	(&#2453;) &#2476;&#2503;&#2468;&#2472;
	</div>
	<div style="float: left; width:240px; border-left:1px solid #000000;" > 
	  <table width="239" cellspacing="0" cellpadding="0">
	  <tr>
	  	<td width="160">&#2478;&#2498;&#2482; &#2476;&#2503;&#2468;&#2472; </td>
		<td>:</td>
	  	<td width="70" align="center"><font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["basic_sal"];   ?> </font></td>
	</tr>
	<tr>
	  	<td width="160">&#2476;&#2494;&#2524;&#2496; &#2477;&#2494;&#2524;&#2494; </td>
	  	<td>:</td>
		<td width="12" align="center">  <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["house_r"];   ?> </font></td>
	</tr>
	  
	  <tr>
	  	<td width="160">&#2458;&#2495;&#2453;&#2495;&#2510;&#2488;&#2494; &#2477;&#2494;&#2468;&#2494;	  	  </td>
	  	<td>:</td>
		<td width="12" align="center"><font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["medical_a"];   ?> </font> </td>
	</tr>
	 <tr>
	  	<td width="160"> যাতায়াত ভাতা</td>
	  	<td>:</td>
		<td width="12" align="center"><font style="font-family: SutonnyMJ;"><?php echo $trans_allow = $rows["trans_allow"];   ?> </font> </td>
	</tr>
    
    <tr>
	  	<td width="160">খাদ্য ভাতা</td>
	  	<td>:</td>
		<td width="12" align="center"><font style="font-family: SutonnyMJ;"><?php echo $food_allow = $rows["food_allow"];   ?> </font> </td>
	</tr> 
	  <tr>
	  	<td width="160" height="14">&#2478;&#2507;&#2463;</td>
	  	<td>:</td>
		<td width="12" align="center">  <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["gross_sal"];   ?> </font></td>
	</tr>
  </table>
	
	</div>
	</div>
   
   <div style="border-bottom:1px solid #000000;"><table width="295" cellspacing="0" cellpadding="0">
    <tr>
      <td width="239"><span style="position:relative; left:5px; font-weight:bold;"> (&#2454;)&#2437;&#2472;&#2497;&#2474;&#2488;&#2509;&#2469;&#2495;&#2468;&#2495; &#2453;&#2480;&#2509;&#2468;&#2472; </span>
      <td width="38"><font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["abs_deduction"];   ?> </font></td>
    </tr></table></div>
		
  <div style="border-bottom:1px solid #000000;"><table width="295" cellspacing="0" cellpadding="0">
    <tr>
      <td width="239"><span style="position:relative; left:5px; font-weight:bold;"> (&#2455;) &#2474;&#2509;&#2480;&#2470;&#2503;&#2527; &#2476;&#2503;&#2468;&#2472; </span>
      <td width="38">  <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["pay_wages"];   ?> </font>                   </td>
    </tr></table></div>
  <div style="width:100%; border-bottom:1px solid #000000; height:auto; overflow:hidden;">
	
	<div style="float:left; width:55px; height: auto; position:relative; left:5px; top:30px; font-weight:bold;"> (&#2456;) &#2477;&#2494;&#2468;&#2494; </div>
	<div style="float: left; width:240px; border-left:1px solid #000000;" > 
	  <table width="240">
	
	<tr>
	  	<td width="154">&#2489;&#2494;&#2460;&#2495;&#2480;&#2494; &#2476;&#2507;&#2472;&#2494;&#2488; </td>
	  	<td>:</td>
		<td width="70" align="center"> <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["att_bonus"];   ?></font> </td>
	</tr>
	
	
	  
	  <tr>
	  	<td width="154">&#2437;&#2472;&#2509;&#2479;&#2494;&#2472;&#2509;&#2479;</td>
	  	<td>:</td>
		<td width="70" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["others_allaw"];   ?> </font>  </td>
	
	  
	 <tr>
	  	<td width="154">&#2478;&#2507;&#2463; &#2477;&#2494;&#2468;&#2494; </td>
	  	<td>:</td>
		<td width="70" align="center"> <font style="font-family: SutonnyMJ;"><?php echo $total_days = $rows["total_allaw"];   ?> </td>
	</tr>
	
  </table>
	
	</div>
	</div>
	
	<div style="border-bottom:1px solid #000000;"><table width="298" cellspacing="0" cellpadding="0">
    <tr>
      <td width="239"><span style="position:relative; left:5px; font-weight:bold;"> (&#2457;) &#2451;&#2477;&#2494;&#2480; &#2463;&#2494;&#2439;&#2478;</span>
      </td><td width="38"> 
	  <span style="font-family: SutonnyMJ; " > <?php echo $total_days = $rows["ot_amount"];   ?> </span>
	  </td>
    </tr></table></div>
	<div style="border-bottom:1px solid #000000;"><table width="298">
    <tr>
      <td width="243"><span style="position:relative; left:2px; font-weight:bold;"> (&#2458;) &#2478;&#2507;&#2463; &#2476;&#2503;&#2468;&#2472;</span> &nbsp;(&#2455; + &#2456; + &#2457; ) 
      <td width="43"><font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["gross_pay"];   ?> </font></td>
    </tr></table></div>
	
	<div style="width:100%; border-bottom:1px solid #000000; height:auto; overflow:hidden;">
	
	<div style="float:left; width:53px; position:relative; left:5px; top:25px; font-weight:bold;">(&#2459;) &#2453;&#2480;&#2509;&#2468;&#2472; </div>
	<div style="float: left; width:240px; border-left:1px solid #000000;" > 
	  <table width="237">
	  
	<tr>
	  	<td width="152"> &#2437;&#2455;&#2509;&#2480;&#2496;&#2478; </td>
	  	<td >:</td>
		<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["adv_deduct"];   ?> </font></td>
	</tr>
	  
	  <tr>
	  	<td width="152">&#2437;&#2472;&#2509;&#2479;&#2494;&#2472;&#2509;&#2479;</td>
	  	<td >:</td>
		<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["others_deduct"];   ?> </font></td>
	</tr>
	  <tr>
	  	<td width="154">স্ট্যাম্প</td>
	  	<td>:</td>
		<td width="70" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $stamp_value = 0;   ?> </font>  </td>
	</tr>
	  
	  <tr>
	  	<td width="152">&#2474;&#2509;&#2480;&#2477;&#2495;&#2465;&#2503;&#2472;&#2509;&#2463; &#2475;&#2494;&#2472;&#2509;&#2465; </td>
	  	<td >:</td>
		<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["provident_fund"];   ?> </font></td>
	</tr>
	
	 <tr>
	  	<td width="152">&#2478;&#2507;&#2463; &#2453;&#2480;&#2509;&#2468;&#2472; </td>
	  	<td >:</td>
		<td width="59" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["total_deduct"]+$stamp_value;   ?> </font></td>
	</tr>
	
  </table>
	
	</div>
	</div>
	
	<div><table width="295" height="17" cellspacing="0" cellpadding="0">
    <tr>
      <td width="215">&#2488;&#2480;&#2509;&#2476;&#2478;&#2507;&#2463; &#2474;&#2509;&#2480;&#2470;&#2503;&#2527;  (&#2458;- &#2459; ) </td>
      <td >:</td>
	  <td width="78" align="center"> <font style="font-family: SutonnyMJ;" > <?php echo $total_days = $rows["net_pay"]-$stamp_value;   ?> </font></td>
    </tr></table></div>
  </div>
  <div style="text-align:left; font-size:14px; padding-top:15px;">Employee Signature :</div>
</div>
</div>
	</td>
<?php
	echo "</tr>";

	if ($count % 2 == 0 && $count < count($values)) {
		echo '</table>';
		echo '<div class="page-break"></div>';
		echo '<table align="left" border="0" cellpadding="0" cellspacing="0">';
	}
}

?>
</table>


</body>
</html>
