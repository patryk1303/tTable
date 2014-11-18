<?php

	$currUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$currUrl = str_replace("/lang","",$currUrl);

	header("Location: $currUrl"); /* Redirect browser */

	/* Make sure that code below does not get executed when we redirect. */
	exit;

?>