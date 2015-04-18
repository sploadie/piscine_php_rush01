<?PHP

function get_database()
{
	if (($handle = fopen("../db/products.csv", "r")) == false) {
		echo "DB FETCH ERROR\n";
		exit ;
	}
	while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
		# $product_database['Small Fries']['price']
		$product_database[$data[0]]['price'] = $data[1];
		$product_database[$data[0]]['tags'] = explode(",", $data[2]);
		$product_database[$data[0]]['image_url'] = $data[3];
		$product_database[$data[0]]['description'] = $data[4];
	}

	fclose($handle);

	return $product_database;
}

?>