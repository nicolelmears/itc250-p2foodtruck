<?php

require 'config_inc.php';
include 'items.php';

if(isset($_REQUEST['act']))
{
  $myAction = (trim($_REQUEST['act']));
}else
{
  $myAction = "";
}

switch ($myAction){
  case "display":
    showData();
    break;
  default:
    showForm();

}

function showForm(){
  global $config;
  get_header();

  echo
	'<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.YourName,"Please Enter Your Name")){return false;}
			return true;//if all is passed, submit!
		}
	</script>
	<h3 align="center">' . smartTitle() . '</h3>
	<p align="center">Please Enter Quantities of Each Item</p>
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
             ';


		foreach($config->items as $items)
    {
    echo '<p>' . $items->Name . ' $' . $items->Price . '<input type="text" name="item_' . $items->ID . '" /></p>';
    }
    //Loops through the Extras
            foreach($items->Extras as $extra)
              {
                  echo '<p>' . $extra . '<input type="checkbox" name="extra_' . $extra . '" /></p>';
              }

    echo '
      <p>
        <input type="submit" value="Submit Quantities"><em>(<font color="red"><b>*</b> required field</font>)</em>
      </p>
    <input type="hidden" name="act" value="display" />
    </form>
    ';
    get_footer();
}

function showData(){
  get_header();

  echo '<h3 align="center">' . smartTitle() . '</h3>';

	foreach($_POST as $name => $value)
    {
      if(substr($name,0,5)=='item_')
      {
      $name_array = explode('_',$name);
      $ID = (int)$name_array[1];
      $Price = "";

      //calculate totals
      $total = calculateTotal($value, $Price);
      $total += (int)$total;

}

}
echo '<p>You ordered ' . $value . 'of item number '. $ID . '</p>';
echo '<p>Your total is: $' . $total . '</p>';

echo '<p align="center"><a href="' . THIS_PAGE . '">RESET</a></p>';
get_footer(); #defaults to footer_inc.php


}

function calculateTotal($value, $Price){
  global $config;
    foreach($config->items as $items){
      $subTotal = $value * $items->Price;
      $taxRate = ($subTotal * 10.09) / 100;
      $total = $taxRate + $subTotal;
      return round($total);
}
}


?>
