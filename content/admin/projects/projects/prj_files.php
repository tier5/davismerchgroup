<?php
require 'Application.php';
extract($_POST);

require 'get_project_files.php';

echo $html;
?>