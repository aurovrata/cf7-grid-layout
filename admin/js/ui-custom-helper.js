/*
* Template file from CF7 Smart Grid plugin
* cf7-grid-layout/admin/js/ui-custom-helper.js
*/
var cf7sgCustomHelperModule = (function (cch) {
  // add functionality for your tag, replace 'your_plugin_tag' with the tap type.
	// example [your_plugin_tag field-name id:field-id class:field-class "additional options"]
  // cch.<your_plugin_tag> = function(tag){
	//   //basic regex to match a shortcode tag.
  //   const regex = /\[(your_plugin_tag|your_plugin_tag\*)\s(.[^\s\"\'\]]*)(?:\s(.[^\]]*))\]/img;
  //   let m, helpers={'js':[],'php':[]};
  //   while ((m = regex.exec(tag)) !== null) {
	//     //match your shortcode string.
	//     helpers.php[helpers.php.lenght] = 'class-name-for-grid-filter';
  //   }
	//   return helpers;
	// }

	//example: [map your-location show_address "lat:12.45;lng:80.09"]
	// cch.map = function(shortcode){
	//   const regex = /\[(map|map\*)\s(.[^\s\"\'\]]*)(?:\s(.[^\]]*))\]/img;
	//   let match, helpers={'js':[],'php':[]};
	//   while ((match = regex.exec(shortcode)) !== null) {
	//     if(match.indexOf('show_address')>-1){
	//       helpers.php[helpers.php.length] = 'map-show_address';
	//     }
	//   }
	//   return helpers;
	// }
	return cch;
}(cf7sgCustomHelperModule || {}));
