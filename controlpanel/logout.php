<?php

setcookie("cploggedin","yes",time()-3600,"/");
header("Location: /controlpanel/");
?>