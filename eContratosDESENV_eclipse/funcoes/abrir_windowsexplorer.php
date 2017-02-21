<?php
$comando = @$_GET["comando"];
//echo $pasta;
exec("Explorer.exe " . $comando);
?>

<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript">
window.close();
</SCRIPT>
</HEAD>
</HTML>
