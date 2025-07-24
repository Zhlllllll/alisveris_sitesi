<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "partials/_header.php";
include "partials/_navbar.php";
?>

<!-- Tailwind etkisi iframe içinde -->
<iframe src="tailwind-bolumu.php" id="tailwindIframe" style="width:100%; border:none; " class="mt-6" onload="this.style.height = this.contentWindow.document.body.scrollHeight + 'px';"></iframe>


</body>
</html>
