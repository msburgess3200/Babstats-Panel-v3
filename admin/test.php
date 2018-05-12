<?php
// $arr = array(
// "k"=>array("a","b","c","d","e","f"),
// "a"
// );
$arr = array(
"k"=>array("z"),
"z"
);
$a =(json_encode($arr));
print $a;
print_r($arr);

if (array_key_exists("k", $arr)){
	echo "Permission found! Searching for sub-permissions...";
	if (in_array("z",$arr['k'])){
		echo "God mode...";
	}else{
		echo "Sub-pemissions found...\n"	;
		if (in_array("a",$arr['k'])){
			echo "Inbox\n";
		}
		if (in_array("b",$arr['k'])){
			echo "Compose\n";
		}
		if (in_array("c",$arr['k'])){
			echo "Reply\n";
		}
		if (in_array("d",$arr['k'])){
			echo "Sent\n";
		}
		if (in_array("e",$arr['k'])){
			echo "Trash\n";
		}
		if (in_array("f",$arr['k'])){
			echo "View\n";
		}
	}
}else{
	echo "Nothing.";
}

// echo "\n";
// print_r(json_decode($a, true));
// var_dump(json_decode($a, true));
// $aa = json_decode($a,true);

// if (in_array("z", $aa['k'])){
	// echo "It's there";
// }else{
	// echo "Not there.";
// }

// print_r $aa['k'];
?>