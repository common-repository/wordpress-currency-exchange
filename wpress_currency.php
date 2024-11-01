<?php
/*
Plugin Name: Word Press Currency Exchange
Plugin URI: http://www.grupomayanstyle.com/wordpress-currency-exchange/
Description: Plugin that lets you to make currency convertions rates.  
Author: Niel Hendrich
Version: 1.0
Author URI: http://www.grupomayanstyle.com/
*/


include("currencyexchange_class.php");

// **************************************
// ***** 
// ***** CURRENCY WIDGET
// *****
// **************************************

function wpcurrency_getwidget()
{	
		$DefaultCurrency = get_option("wpcurrency_default");
				
		$DefaultAmount	 = get_option("wpcurrency_default_amount");
		if (!$DefaultAmount)
		   $DefaultAmount = "1";
		
		$cx=new currencyExchange();
		$cx->getData();
		  
?>
<form name="wpcurrency">
	<div style="background: url('<?php echo get_option('siteurl') 
			. '/wp-content/plugins/wordpress_currency/money-exchange.png' ?> '); font-size:14px; text-align:center; width:200px; height:250px;  font-family: Verdana">
			<br><font color="darkblue"><b>Currency Exchange</b></font><br><br>
		<table width="100%" style="font-size:12px;" height="128">
			<tr>
				<td>
					
					<b>Amount</b>
					 
				</td>
				<td>
					<input type="text" size="10" style="border: 2px solid #FFFFFF" 
						   name="WPRESS_CURRENCY_AMOUNT" value="<?php echo $DefaultAmount ?>">
				</td>
			</tr>
			<tr>
				<td>

					<b>From</b>

				</td>
				<td>
				<?php 
						echo getCurrencyList( "WPRESS_CURRENCY_FROM", get_option("wpcurrency_default")); 
					?>
				</td>
			</tr>
			<tr>
				<td>
					
					<b>To</b>
					
				</td>
				<td>
					<?php 
						echo getCurrencyList( "WPRESS_CURRENCY_TO", ""); 
					?>
				 </td>
			</tr>
			<tr>
				<td>
					
					<b>Total</b>
					
				</td>
				<td>
					<input type="text" size="10" style="border: 2px solid #FFFFFF" name="WPRESS_CURRENCY_TOTAL">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<br><br>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="button" style="border: 2px solid #C0C0C0"
						   name="calculate" value="Calculate Rate !" onclick="getConvertion();"> 
				</td>
			</tr>
		</table>
	</div>
	
</form>
<?
     
}

function getCurrencyList($fieldName, $DefaulCur)
{
	$data = '<select name="' . $fieldName . '" style="border-style: solid; border-width: 2px" size="1">';
					  
	$currencies = getCurrencyNames();
	foreach ($currencies as $key => $currencyName)
	{	
		if ($key == $DefaulCur)
			$data .= '<option value="' . $key .'" SELECTED>' . $currencyName . '</option>' . "\n";
		else
			$data .= '<option value="' . $key .'">' . $currencyName . '</option>' . "\n";
	}
					  
	$data .=  '</select>';
	
	return $data;
} 

function wpcurrency_widget($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;?><?php echo $after_title;
  echo wpcurrency_getwidget();
  echo $after_widget;
}

// **************************************
// ***** 
// ***** CURRENCY JAVASCRIPT WIDGET
// *****
// **************************************
function wpcurrency_convertion_scripts()
{  
	
?>
	<script type="text/javascript" src="<?php echo get_option("siteurl") . '/wp-content/plugins/wordpress_currency/wpress_currency.js' ?>">
	</script>;
	
	<script type="text/javascript">
		
		
		function getConvertion()
		{
			    var url = "<?php echo get_option("siteurl") . 
									  '/wp-content/plugins/wordpress_currency/wpress_currency_mexchange.php' ?>";
				
						  
			    var CurrencyFrom = document.forms["wpcurrency"].WPRESS_CURRENCY_FROM.value;
			    var CurrencyTo   = document.forms["wpcurrency"].WPRESS_CURRENCY_TO.value;
			    var Amount 		 = document.forms["wpcurrency"].WPRESS_CURRENCY_AMOUNT.value;
			  
				
				document.forms["wpcurrency"].WPRESS_CURRENCY_TOTAL.value = 
						wpcur_makeExchange(url,CurrencyFrom, CurrencyTo, Amount);
		
		}
			
	</script>
<?php	
};


function wpcurrency_widget_control()
{
  
  $wpcurrency_default 		 = get_option("wpcurrency_default");
  if (!$wpcurrency_default)
		$wpcurrency_default ="";

  $wpcurrency_default_amount = get_option("wpcurrency_default_amount");
  if (!$wpcurrency_default_amount)
		$wpcurrency_default_amount = "1";
		
  if ($_POST['wpcurrency-Submit'])
  {
	    update_option("wpcurrency_default",  $_POST['wpcurrency_default']);
		$wpcurrency_default = get_option("wpcurrency_default");
		
		update_option("wpcurrency_default_amount",  $_POST['wpcurrency_default_amount']);
		$wpcurrency_default_amount = get_option("wpcurrency_default_amount");
  }
  
  ?>
   <p>
     <label for="wpcurrency_default_amount">Default Amount</label>
     	<input type="text" size="6" name="wpcurrency_default_amount" id="wpcurrency_default_amount"
     		   value="<?php echo $wpcurrency_default_amount ?>"><br><br>
    <label for="wpcurrency_default">Default Currency</label>
    <?php 
    	echo getCurrencyList("wpcurrency_default", $wpcurrency_default) 
    ?><br>
    
    
    
    <input type="hidden" id="wpcurrency-Submit" name="wpcurrency-Submit" value="1" />
  </p>
  <?php
};


function wpcurrency_init()
{
  register_sidebar_widget(__('WordPress Currency Exchange'), 'wpcurrency_widget');
  register_widget_control('WordPress Currency Exchange', 'wpcurrency_widget_control', 300, 200 );
}

add_action('wp_footer', 'wpcurrency_convertion_scripts');
add_action("plugins_loaded", "wpcurrency_init");


?>
